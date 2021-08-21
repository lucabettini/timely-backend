<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
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
            'timezone' => 'max:255'
        ]);

        // STORE USER IN DATABASE
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'timezone' => $request->timezone
        ]);

        // CREATE JWT TOKEN 
        $secret = env('JWT_SECRET');
        $now = new DateTime();
        $payload = array(
            "iat" => $now->getTimestamp(),
            "user" => $request->email
        );
        $jwt = JWT::encode($payload, $secret);

        return response([
            "status" => "success"
        ])->header('jwt', $jwt);
    }
}
