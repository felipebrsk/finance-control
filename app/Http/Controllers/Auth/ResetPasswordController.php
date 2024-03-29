<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\PasswordChangedJob;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Contracts\Services\UserServiceInterface;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * The user service interface.
     *
     * @var \App\Contracts\Services\UserServiceInterface
     */
    private $userServiceInterface;

    /**
     * Create new class instance.
     *
     * @param \App\Contracts\Services\UserServiceInterface $userService
     * @return void
     */
    public function __construct(UserServiceInterface $userServiceInterface)
    {
        $this->userServiceInterface = $userServiceInterface;
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(
        Request $request,
        string $response
    ): JsonResponse {
        $notifiable = $this->userServiceInterface->findByEmail($request->email);

        PasswordChangedJob::dispatch($notifiable);

        return new JsonResponse(['message' => trans($response)], 200);
    }
}
