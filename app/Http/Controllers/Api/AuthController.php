<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

/**
 * @property User $user
 */
class AuthController extends Controller
{
    public function __construct(
        private UserInterface $user
    ) {
    }

    /**
     * handle user registration
     * 
     * @param \App\Http\Requests\RegisterUserRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterUserRequest $request)
    {
        /** @var array $validated */
        $validated = $request->validated();

        $user = $this->user->create($validated);

        return response()->json([
            'user' => new UserResource($user)
        ], 201);
    }
}
