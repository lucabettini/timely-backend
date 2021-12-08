<?php

namespace App\Modules\Users\Services;

use App\Modules\Users\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class CreateUserService
{
    private $repository;

    private $service;

    public function __construct(UserRepository $repository, TokenService $token_service)
    {
        $this->repository = $repository;
        $this->service = $token_service;
    }

    public function create($name, $email, $password, $timezone)
    {
        $this->repository->createUser(
            $name,
            $email,
            Hash::make($password),
            $timezone
        );

        return $this->service->create($email);
    }
}
