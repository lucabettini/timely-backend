<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Http\Request;
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
}
