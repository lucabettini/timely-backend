<?php

namespace App\Modules\Users\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function createUser($name, $email, $hashedPassword)
    {
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            // 'timezone' => $request->timezone
        ]);
    }

    public function isSamePassword($password, $hashedPassword)
    {
        return Hash::check($password, $hashedPassword);
    }
}
