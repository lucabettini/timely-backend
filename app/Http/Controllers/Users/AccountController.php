<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\EditAccountRequest;
use App\Modules\Users\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function update(EditAccountRequest $request)
    {
        // Check if the password entered for auth confirmation is the same
        if (!$this->repository->isSamePassword($request->password, $request->user()->password)) {
            return response([
                'message' => 'The entered password is not valid'
            ], 401);
        }

        $this->repository->editAccount($request->name, $request->email, $request->user());

        return response([
            'message' => 'User updated successfully'
        ]);
    }

    public function destroy(Request $request)
    {
        // Get token
        $jwt = $request->header('jwt');

        $secret = env('JWT_SECRET');
        $token = JWT::decode($jwt, $secret, array('HS256'));


        // Delete user from DB
        $request->user()->delete();

        // Logout user
        // $this->repository->addTokenToBlackList($token->jti);

        // Return response
        return response([
            'message' => 'User deleted successfully'
        ]);
    }
}
