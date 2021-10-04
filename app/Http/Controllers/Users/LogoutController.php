<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Modules\Users\Repositories\UserRepository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    public function store(Request $request)
    {
        $jwt = $request->header('jwt');

        // Decode the token 
        $secret = config('auth.jwt_secret');
        $token = JWT::decode($jwt, $secret, array('HS256'));

        // Store the ID in the the revoked_tokens table
        $this->repository->addTokenToBlackList($token->jti);

        return response([
            'message' => 'Logout successfull'
        ]);
    }
}
