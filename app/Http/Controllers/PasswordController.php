<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Utils\ResponseUtils;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{

    public function forgotPassword(Request $request)
    {
        $request->validate([
            "email" => "required|email"
        ]);
        $status = Password::sendResetLink(
            $request->only("email")

        );
        if ($status == Password::RESET_LINK_SENT) {
            return ResponseUtils::formatResponse(message: "Lien d'activation envoyé par mail.!");
        }
        throw ValidationException::withMessages([
            "email" => [trans("$status")]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60)
                ])->save();
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return ResponseUtils::formatResponse(message: "Mot de passe modifié avec succés !");
        }
        return ResponseUtils::formatResponse(success:false,message: $status, status: 500);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:6|confirmed|different:old_password',
        ]);
        $user = $request->user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'isFirstlyConnected' => false,
                'password' => Hash::make($request->password),
            ]);
            return ResponseUtils::formatResponse(message: "Mot de passe modifié avec succés !");
        } else
            return ResponseUtils::formatResponse(success: false, message: "L'ancien mot de passe est incorrect ", status: 400);
    }
}
