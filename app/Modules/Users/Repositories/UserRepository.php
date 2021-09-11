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

    public function createUser($name, $email, $hashed_password)
    {
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $hashed_password,
        ]);
    }

    public function isSamePassword($password, $hashed_password)
    {
        return Hash::check($password, $hashed_password);
    }
}
