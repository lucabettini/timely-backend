<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ResetPasswordRequest;
use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password as FacadesPassword;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function change(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
        ]);

        if (!$this->repository->isSamePassword($request->old_password, $request->user()->password)) {
            return response([
                'message' => 'The old password is not valid'
            ], 401);
        }

        // Change password in DB 
        $this->repository->changePassword($request->password, $request->user()->id);

        return response([
            'message' => 'Password changed'
        ], 200);
    }

    public function forgot(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = FacadesPassword::sendResetLink(
            $request->only('email')
        );

        // Return either confirmation or error message
        return $status === FacadesPassword::RESET_LINK_SENT
            ? response(['message' => 'Reset link sent'], 200)
            : response(['message' => 'There was a problem while sending the email'], 502);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = $this->repository->resetPassword($request);

        // Return either confirmation or error message
        return $status == FacadesPassword::PASSWORD_RESET
            ? response(['message' => 'Password changed'], 200)
            : response(['message' => 'The entered data are invalid'], 400);
    }
}
