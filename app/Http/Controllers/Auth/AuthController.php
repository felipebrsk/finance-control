<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\{Request, JsonResponse};
use App\Exceptions\Auth\LoginFailedException;
use App\Contracts\Http\Controllers\AuthControllerInterface;

class AuthController extends Controller implements AuthControllerInterface
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $this->validateLogin($request);

        return $this->responseToken($this->attemptLogin($request));
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request): void
    {
        $request->validate([
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string>
     */
    protected function credentials(Request $request): array
    {
        return $request->only($this->username(), 'password');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function attemptLogin(Request $request): string
    {
        return Auth::guard('api')->attempt(
            $this->credentials($request)
        );
    }

    /**
     * Register the user in application.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($picture = issetGetter('avatar', $data)) {
            $data['avatar'] = s3Service()->create($picture, 'avatars', 'private');
        }

        $user = User::create($data);

        $token = (string)Auth::guard('api')->login($user);

        return $this->responseToken($token, 201);
    }

    /**
     * Refresh user jwt token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $token = Auth::guard('api')->refresh();

        return $this->responseToken($token);
    }

    /**
     * Get the auth user info.
     * 
     * @return \App\Http\Resources\UserResource
     */
    public function me(): UserResource
    {
        return UserResource::make(Auth::user());
    }

    /**
     * The token response.
     *
     * @param string $token
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\Auth\LoginFailedException
     */
    private function responseToken(string $token, int $code = 200): JsonResponse
    {
        return $token ?
            response()->json([
                'data' => [
                    'token' => $token
                ]
            ], $code) :
            throw new LoginFailedException();
    }

    /**
     * Define the username to login.
     * 
     * @return string
     */
    protected function username(): string
    {
        $credential = request('credential');

        if (filter_var($credential, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'username';
        }

        request()->merge([$field => $credential]);

        return $field;
    }
}
