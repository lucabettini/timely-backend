<?php

namespace App\Modules\Users\Services;

use DateTime;
use Firebase\JWT\JWT;

class TokenService
{

    private $secret = env('JWT_SECRET');

    private function getDate()
    {
        $now = new DateTime();
        return $now->getTimestamp();
    }

    public function create($email)
    {

        $payload = array(
            "iat" => $this->getDate(),
            "user" => $email
        );
        $jwt = JWT::encode($payload, $this->secret);

        return $jwt;
    }
}
