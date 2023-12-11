<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Utils\ResponseUtils;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail())
            return ResponseUtils::formatResponse(message: "Compte déjà activé !");
        $request->user()->sendEmailVerificationNotification();
        return ResponseUtils::formatResponse(message: "Lien d'activation envoyé !");
    }

    public function notice()
    {
        return ResponseUtils::formatResponse(message: "Avant de continuer, veuillez vérifier votre e-mail un lien de vérification vous a été envoyé.");
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        if (!hash_equals((string) $request->hash, sha1($user->getEmailForVerification()))) {
            return ResponseUtils::formatResponse(success: false, message: 'Non autorisé!');
        }

        if ($user->hasVerifiedEmail()) {
            return ResponseUtils::formatResponse(success: false, message: 'Compte déjà vérifié !');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        return ResponseUtils::formatResponse(message: 'Compte vérifié avec succès !');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            "old_email" => ['required', 'email', 'exists:users,email'],
            "new_email" => ['required', 'email', 'different:old_email', 'unique:users,email'],
            "password" => ['required', 'current_password'],
        ]);
        $user = User::where('email', $request->old_email)->first();
        if ($user) {
            $user->email = $request->new_email;
            $user->email_verified_at = null;
            event(new Registered($user));
            $user->save();
        }
        return ResponseUtils::formatResponse(message: 'email modifé avec succès!', data: new UserResource($user));
    }
}
