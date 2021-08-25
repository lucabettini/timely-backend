<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Modules\Users\Services\CreateUserService;

class RegisterController extends Controller
{

    private $service;

    public function __construct(CreateUserService $service)
    {
        $this->service = $service;
    }

    public function store(RegisterRequest $request)
    {
        // Create a new user and JWT
        $jwt = $this->service->create(
            $request->name,
            $request->email,
            $request->password
        );

        // Return response 
        return response([
            "status" => "success"
        ])->header('jwt', $jwt);
    }
}
