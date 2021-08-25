<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Users\Repositories\UserRepository;
use App\Modules\Users\Services\TokenService;
use Illuminate\Http\Request;


class LoginController extends Controller
{

    private UserRepository $repository;
    private TokenService $service;


    public function store(Request $request)
    {
        // VALIDATION
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // GET USER
        $user = $this->repository->getUserByEmail($request->email);
        if (!$user) {
            return response([
                "message" => "user not found"
            ], 400);
        }

        // CHECK PASSWORD
        if (!$this->repository->isSamePassword($request->password, $user->password)) {
            return response([
                "message" => "invalid credentials"
            ], 400);
        }

        // CREATE JWT
        $jwt = $this->service->create($request->email);

        // Attach to header and return success message
        return response([
            "status" => "success"
        ])->header('jwt', $jwt);
    }
}
