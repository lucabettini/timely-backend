<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\LoginRequest;
use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Users\Services\TokenService;

class LoginController extends Controller
{
    private $repository;
    private $service;

    public function __construct(UserRepository $repository, TokenService $token_service)
    {
        $this->repository = $repository;
        $this->service = $token_service;
    }


    public function store(LoginRequest $request)
    {
        // Get user from DB 
        $user = $this->repository->getUserByEmail($request->email);
        if (!$user) {
            return response([
                "message" => "user not found"
            ], 400);
        }

        // Check password 
        if (!$this->repository->isSamePassword($request->password, $user->password)) {
            return response([
                "message" => "invalid credentials"
            ], 400);
        }

        // Create JWT, attach to header and return response
        $jwt = $this->service->create($request->email);
        return response([
            "status" => "success"
        ])->header('jwt', $jwt);
    }
}
