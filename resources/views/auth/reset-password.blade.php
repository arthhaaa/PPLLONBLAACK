@extends('layouts.app')

@section('title', 'Buat Password Baru - Long Black')

@section('content')
<main class="password-reset-page">
    <section class="password-reset-shell">
        <div class="password-reset-brand">
            <a href="{{ route('home') }}" class="password-reset-logo">
                <img src="{{ asset('img/long-black-logo.png') }}" alt="Long Black">
            </a>
            <div>
                <span>Long Black</span>
                <h1>Buat Password Baru</h1>
                <p>Gunakan password baru yang mudah kamu ingat dan tetap aman untuk akun Long Black.</p>
            </div>
        </div>

        <div class="reset-card">
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $email) }}"
                        placeholder="email@example.com"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Minimal 6 karakter"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Ulangi password baru"
                        required
                    >
                </div>

                <button type="submit" class="reset-submit">
                    Simpan Password Baru
                </button>
            </form>

            <a href="{{ route('login') }}" class="reset-back-link">Kembali ke login</a>
        </div>
    </section>
</main>
@endsection

@section('styles')
<style>
    body {
        background: #f8f3ed;
    }

    .header_area,
    .footer-area {
        display: none;
    }

    .password-reset-page {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 32px 16px;
        background:
            linear-gradient(135deg, rgba(75, 46, 43, 0.08), rgba(182, 136, 52, 0.1)),
            #f8f3ed;
    }

    .password-reset-shell {
        width: min(100%, 980px);
        display: grid;
        grid-template-columns: minmax(0, 0.9fr) minmax(320px, 1fr);
        overflow: hidden;
        border-radius: 8px;
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.12);
        box-shadow: 0 18px 42px rgba(75, 46, 43, 0.12);
    }

    .password-reset-brand {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 40px;
        min-height: 520px;
        padding: 34px;
        background: #4b2e2b;
        color: #ffffff;
    }

    .password-reset-logo img {
        width: 104px;
        height: auto;
    }

    .password-reset-brand span {
        display: block;
        color: #f1c27d;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .password-reset-brand h1 {
        color: #ffffff;
        font-size: 38px;
        line-height: 1.15;
        font-weight: 900;
        margin-bottom: 12px;
    }

    .password-reset-brand p {
        color: rgba(255, 255, 255, 0.78);
        font-size: 16px;
        line-height: 1.7;
        margin: 0;
    }

    .reset-card {
        align-self: center;
        padding: 38px;
    }

    .reset-card label {
        color: #4b2e2b;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .reset-card .form-control {
        width: 100%;
        height: 52px;
        border: 1px solid rgba(75, 46, 43, 0.18);
        border-radius: 8px;
        color: #333333;
        padding: 0 16px;
    }

    .reset-card .form-control:focus {
        border-color: #b68834;
        box-shadow: 0 0 0 0.2rem rgba(182, 136, 52, 0.16);
    }

    .reset-submit {
        width: 100%;
        min-height: 54px;
        border: 0;
        border-radius: 8px;
        background: #8c5a3c;
        color: #ffffff;
        font-weight: 900;
        text-transform: uppercase;
        margin-top: 12px;
        cursor: pointer;
    }

    .reset-submit:hover {
        background: #744832;
    }

    .reset-back-link {
        display: inline-flex;
        margin-top: 20px;
        color: #4b2e2b;
        font-weight: 800;
    }

    @media (max-width: 768px) {
        .password-reset-page {
            align-items: start;
            padding: 18px 12px;
        }

        .password-reset-shell {
            grid-template-columns: 1fr;
        }

        .password-reset-brand {
            min-height: auto;
            padding: 28px;
        }

        .password-reset-brand h1 {
            font-size: 30px;
        }

        .reset-card {
            padding: 28px;
        }
    }

    @media (max-width: 420px) {
        .password-reset-brand,
        .reset-card {
            padding: 22px;
        }

        .password-reset-logo img {
            width: 88px;
        }

        .password-reset-brand h1 {
            font-size: 26px;
        }
    }
</style>
@endsection
