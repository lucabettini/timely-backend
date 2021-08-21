<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use DateTime;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function App\Http\Controllers\Auth\utils\sendToken;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        // VALIDATION
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // GET USER
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response([
                "message" => "user not found"
            ], 400);
        }

        // CHECK PASSWORD
        if (!Hash::check($request->password, $user->password)) {
            return response([
                "message" => "invalid credentials"
            ], 400);
        }

        // Create JWT
        $secret = env('JWT_SECRET');
        $now = new DateTime();
        $payload = array(
            "iat" => $now->getTimestamp(),
            "user" => $request->email
        );
        $jwt = JWT::encode($payload, $secret);

        // Attach to header and return success message
        return response([
            "status" => "success"
        ])->header('jwt', $jwt);
    }
}
