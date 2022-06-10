<?php

namespace App\Http\Controllers\Api;

use App\Contracts\UserInterface;
use App\Enums\TokenStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\PersonalAccessTokenResource;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Response;

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
        $userResource = new UserResource($user);

        return $userResource
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * handle user login
     * 
     * @param LoginRequest $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $request->authenticateOrFail();

        /** @var User $user */
        $user = $request->user();

        $tokenName = $request->input('token_name');
        $expiredAt = $request->date('expired_at', 'Y-m-d');

        /** @var \Laravel\Sanctum\NewAccessToken $token */
        $token = $user->createExpirableToken($tokenName, $expiredAt);
        $personalAccessTokenResource = (new PersonalAccessTokenResource($token->accessToken))
            ->additional([
                'token' => [
                    'token' => $token->plainTextToken,
                    'status' => TokenStatusEnum::Active
                ]
            ]);

        return $personalAccessTokenResource
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * handle user logout
     * 
     * @param Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        /** @var PersonalAccessToken $token */
        $token = $user->currentAccessToken();

        $token->delete();

        $personalAccessTokenResource = (new PersonalAccessTokenResource($token))
            ->additional([
                'token' => [
                    'token' => $token->plainTextToken,
                    'status' => TokenStatusEnum::Revoked
                ]
            ]);

        return $personalAccessTokenResource
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
