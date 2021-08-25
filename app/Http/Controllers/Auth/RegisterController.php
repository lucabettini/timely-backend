<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Modules\Users\Services\CreateUserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{

    private $service;

    public function __construct(CreateUserService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        // VALIDATION
        $this->validate($request, [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|max:255|email|unique:users',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->letters()->numbers()
            ],
            // 'timezone' => 'max:255'
        ]);

        // CREATE NEW USER AND JWT
        $jwt = $this->service->create(
            $request->name,
            $request->email,
            $request->password
        );

        // RESPONSE 
        return response([
            "status" => "success"
        ])->header('jwt', $jwt);
    }
}
