<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $credenciales = [
            'username' => $request->username,
            'password' => $request->password,
            'estado'   => 1,
        ];

        if (Auth::attempt($credenciales, $request->boolean('remember'))) {
            $request->session()->regenerate();

           
        }

        return back()
            ->withErrors(['username' => 'Usuario o contraseña incorrectos, o cuenta inactiva.'])
            ->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $rules = [
            'email' => ['required', 'email'],
        ];

        $messages = [
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'Ingresa un correo válido.',
        ];

        $attributes = [
            'email' => 'correo',
        ];

        $request->validate($rules, $messages, $attributes);

        $status = Password::sendResetLink($request->only('email'));

        $statusEs = match ($status) {
            Password::RESET_LINK_SENT => 'Te hemos enviado el enlace para restablecer tu contraseña.',
            Password::INVALID_USER    => 'No encontramos un usuario con ese correo.',
            Password::RESET_THROTTLED => 'Espera un momento antes de volver a intentarlo.',
            default                   => 'No se pudo enviar el enlace. Inténtalo nuevamente.',
        };

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', $statusEs)
            : back()->withErrors(['email' => $statusEs])->withInput();
    }

    public function showReset(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {

        $rules = [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];

        $messages = [
            'token.required'       => 'El token es obligatorio.',
            'email.required'       => 'El correo es obligatorio.',
            'email.email'          => 'Ingresa un correo válido.',
            'password.required'    => 'La contraseña es obligatoria.',
            'password.confirmed'   => 'Las contraseñas no coinciden.',
            'password.min'         => 'La contraseña debe tener al menos 8 caracteres.',
        ];

        $attributes = [
            'email' => 'correo',
            'password' => 'contraseña',
        ];

        $request->validate($rules, $messages, $attributes);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );


        $statusEs = match ($status) {
            Password::PASSWORD_RESET => 'Tu contraseña fue restablecida correctamente.',
            Password::INVALID_TOKEN  => 'El enlace de restablecimiento es inválido o ya expiró.',
            Password::INVALID_USER   => 'No encontramos un usuario con ese correo.',
            default                  => 'No se pudo restablecer la contraseña. Inténtalo nuevamente.',
        };

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', $statusEs)
            : back()->withErrors(['email' => $statusEs])->withInput();
    }
}
