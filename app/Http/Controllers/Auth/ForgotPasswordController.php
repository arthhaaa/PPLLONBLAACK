<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
    
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_delivery' => 'nullable|in:direct,mailtrap',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email tidak ditemukan. Pastikan email sudah terdaftar.']);
        }

        $delivery = $request->input('reset_delivery', 'direct');

        if (! app()->environment('production') && $delivery === 'direct') {
            $token = Password::broker()->createToken($user);
            $resetLink = route('password.reset', [
                'token' => $token,
                'email' => $user->email,
            ]);

            return back()
                ->withInput($request->only('email'))
                ->with('status', 'Link reset password dibuat untuk testing.')
                ->with('reset_link', $resetLink);
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            if (! app()->environment('production') && $delivery === 'mailtrap') {
                return redirect()->away($this->mailtrapInboxUrl());
            }

            return back()->with('status', 'Link reset password sudah dikirim. Silakan cek email kamu.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Email tidak ditemukan. Pastikan email sudah terdaftar.']);
    }

    private function mailtrapInboxUrl(): string
    {
        return env('MAILTRAP_INBOX_URL', 'https://mailtrap.io/inboxes');
    }
    
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }
    
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Link reset password tidak valid atau sudah kedaluwarsa. Silakan minta link baru.']);
    }
}
