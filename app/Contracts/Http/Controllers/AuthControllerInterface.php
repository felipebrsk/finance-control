<?php

namespace App\Contracts\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\{Request, JsonResponse};

interface AuthControllerInterface
{
    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse;

    /**
     * Register the user in application.
     *
     * @param \App\Http\Requests\Auth\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse;

    /**
     * Refresh user jwt token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse;
}
