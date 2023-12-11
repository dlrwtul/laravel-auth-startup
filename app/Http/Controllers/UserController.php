<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Utils\ResponseUtils;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return ResponseUtils::formatResponse(data: UserResource::collection(User::all()),);
    }


    /**
     * Store a new User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        if (Gate::allows('create-user')) {
            $user = User::create(array_merge(
                $request->validated(),
                ['password' => bcrypt('Passer123')]
            ));
            $user->assignRole([$request->roles]);

            event(new Registered($user));
            return ResponseUtils::formatResponse(message: 'Utilisateur inscrit avec succès', status: 201, data: new UserResource($user));
        }
        return ResponseUtils::formatResponse(message: "Vous n'avez pas les droits pour creer ce type d'utilisateur", status: 404,success:false);

    }

    public function show(User $user)
    {
        return ResponseUtils::formatResponse(data: new UserResource($user));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();
        return ResponseUtils::formatResponse(message: 'Utilisateur supprimé avec succès');
    }

    /**
     * modify the user .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $emailChanged = $user->email != $request->validated('email');
        $user->update(["username" => $request->validated()->username]);
        if ($emailChanged) {
            $user->email_verified_at = null;
            event(new Registered($user));
            $user->save();
        }
        return ResponseUtils::formatResponse(message: 'Utilisateur modifié avec succès', status: 201, data: new UserResource($user));
    }

    /**
     * enable or disable the user .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function enableOrDisable(User $user)
    {
        $isUpdated =  $user->update(["isActive" => !$user->isActive]);

        // dd($user->isActive);
        return ResponseUtils::formatResponse(message: $isUpdated && !$user->isActive ? "utilisateur bloqué avec succés" : "utilisateur débloqué avec succés");
    }
}
