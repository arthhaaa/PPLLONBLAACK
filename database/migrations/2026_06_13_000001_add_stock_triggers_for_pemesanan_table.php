<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_before_insert_validate_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_after_insert_reduce_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_before_update_validate_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_after_update_sync_stock');

        DB::unprepared("
            CREATE TRIGGER pemesanan_before_insert_validate_stock
            BEFORE INSERT ON pemesanan
            FOR EACH ROW
            BEGIN
                DECLARE current_stock INT DEFAULT NULL;

                IF NEW.id_produk IS NOT NULL
                    AND COALESCE(NEW.status_transaksi, 'menunggu_pembayaran') <> 'dibatalkan'
                    AND COALESCE(NEW.total_produk, 0) > 0 THEN

                    SELECT CAST(stok_produk AS UNSIGNED)
                    INTO current_stock
                    FROM data_produk
                    WHERE id_produk = NEW.id_produk
                    FOR UPDATE;

                    IF current_stock IS NULL THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'Produk pesanan tidak ditemukan.';
                    END IF;

                    IF current_stock < NEW.total_produk THEN
                        SIGNAL SQLSTATE '45000'
                            SET MESSAGE_TEXT = 'Stok produk tidak mencukupi.';
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER pemesanan_after_insert_reduce_stock
            AFTER INSERT ON pemesanan
            FOR EACH ROW
            BEGIN
                IF NEW.id_produk IS NOT NULL
                    AND COALESCE(NEW.status_transaksi, 'menunggu_pembayaran') <> 'dibatalkan'
                    AND COALESCE(NEW.total_produk, 0) > 0 THEN

                    UPDATE data_produk
                    SET stok_produk = CAST((CAST(stok_produk AS SIGNED) - NEW.total_produk) AS CHAR)
                    WHERE id_produk = NEW.id_produk;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER pemesanan_before_update_validate_stock
            BEFORE UPDATE ON pemesanan
            FOR EACH ROW
            BEGIN
                DECLARE current_stock INT DEFAULT NULL;
                DECLARE old_reserved INT DEFAULT 0;
                DECLARE new_reserved INT DEFAULT 0;
                DECLARE needed_stock INT DEFAULT 0;

                IF OLD.id_produk IS NOT NULL
                    AND COALESCE(OLD.status_transaksi, 'menunggu_pembayaran') <> 'dibatalkan' THEN
                    SET old_reserved = COALESCE(OLD.total_produk, 0);
                END IF;

                IF NEW.id_produk IS NOT NULL
                    AND COALESCE(NEW.status_transaksi, 'menunggu_pembayaran') <> 'dibatalkan' THEN
                    SET new_reserved = COALESCE(NEW.total_produk, 0);
                END IF;

                IF NEW.id_produk IS NOT NULL THEN
                    IF OLD.id_produk <=> NEW.id_produk THEN
                        SET needed_stock = new_reserved - old_reserved;
                    ELSE
                        SET needed_stock = new_reserved;
                    END IF;

                    IF needed_stock > 0 THEN
                        SELECT CAST(stok_produk AS UNSIGNED)
                        INTO current_stock
                        FROM data_produk
                        WHERE id_produk = NEW.id_produk
                        FOR UPDATE;

                        IF current_stock IS NULL THEN
                            SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Produk pesanan tidak ditemukan.';
                        END IF;

                        IF current_stock < needed_stock THEN
                            SIGNAL SQLSTATE '45000'
                                SET MESSAGE_TEXT = 'Stok produk tidak mencukupi.';
                        END IF;
                    END IF;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER pemesanan_after_update_sync_stock
            AFTER UPDATE ON pemesanan
            FOR EACH ROW
            BEGIN
                IF OLD.id_produk IS NOT NULL
                    AND COALESCE(OLD.status_transaksi, 'menunggu_pembayaran') <> 'dibatalkan'
                    AND COALESCE(OLD.total_produk, 0) > 0 THEN

                    UPDATE data_produk
                    SET stok_produk = CAST((CAST(stok_produk AS SIGNED) + OLD.total_produk) AS CHAR)
                    WHERE id_produk = OLD.id_produk;
                END IF;

                IF NEW.id_produk IS NOT NULL
                    AND COALESCE(NEW.status_transaksi, 'menunggu_pembayaran') <> 'dibatalkan'
                    AND COALESCE(NEW.total_produk, 0) > 0 THEN

                    UPDATE data_produk
                    SET stok_produk = CAST((CAST(stok_produk AS SIGNED) - NEW.total_produk) AS CHAR)
                    WHERE id_produk = NEW.id_produk;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_after_update_sync_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_before_update_validate_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_after_insert_reduce_stock');
        DB::unprepared('DROP TRIGGER IF EXISTS pemesanan_before_insert_validate_stock');
    }
};
