<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class CreateUserService
{
    private UserRepository $repository;

    private TokenService $service;

    public function create($name, $email, $password)
    {
        $this->repository->createUser(
            $name,
            $email,
            Hash::make($password),
            // $timezone
        );

        return $this->service->create($email);
    }
}
