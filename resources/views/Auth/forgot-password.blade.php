@extends('layouts.app')

@section('title', 'Lupa Password - Long Black')

@section('content')
<section class="password-reset-page">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="reset-card">
                <div class="reset-card-header">
                    <span>Long Black</span>
                    <h1>Lupa Password</h1>
                    <p>Masukkan email akun kamu. Link reset akan dikirim dan bisa dicek melalui Mailpit saat testing.</p>
                </div>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="email@example.com"
                                required
                                autofocus
                            >
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <button type="submit" class="primary-btn reset-submit">
                            Kirim Link Reset Password
                        </button>
                    </form>

                    <a href="{{ route('login') }}" class="reset-back-link">Kembali ke login</a>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

@section('styles')
<style>
    .password-reset-page {
        padding: 120px 0 80px;
        background: #f8f3ed;
    }

    .reset-card {
        background: #ffffff;
        border: 1px solid rgba(75, 46, 43, 0.12);
        border-radius: 8px;
        padding: 34px;
        box-shadow: 0 16px 36px rgba(75, 46, 43, 0.08);
    }

    .reset-card-header {
        margin-bottom: 24px;
    }

    .reset-card-header span {
        display: block;
        color: #b68834;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0;
        margin-bottom: 8px;
    }

    .reset-card-header h1 {
        color: #4b2e2b;
        font-size: 30px;
        line-height: 1.25;
        margin-bottom: 8px;
    }

    .reset-card-header p {
        color: #777777;
        margin-bottom: 0;
    }

    .reset-card label {
        color: #4b2e2b;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .reset-card .form-control {
        height: 48px;
        border: 1px solid rgba(75, 46, 43, 0.18);
        border-radius: 6px;
        color: #333333;
    }

    .reset-card .form-control:focus {
        border-color: #b68834;
        box-shadow: 0 0 0 0.2rem rgba(182, 136, 52, 0.16);
    }

    .reset-submit {
        width: 100%;
        border: 0;
        margin-top: 8px;
    }

    .reset-back-link {
        display: inline-block;
        margin-top: 18px;
        color: #4b2e2b;
        font-weight: 700;
    }

    @media (max-width: 575px) {
        .password-reset-page {
            padding: 96px 0 60px;
        }

        .reset-card {
            padding: 24px;
        }

        .reset-card-header h1 {
            font-size: 25px;
        }
    }
</style>
@endsection
