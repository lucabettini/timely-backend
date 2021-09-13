<?php

namespace App\Modules\Users\Services;

use DateTime;
use Firebase\JWT\JWT;
use Webpatser\Uuid\Uuid;

class TokenService
{

    private $secret;

    public function __construct()
    {
        $this->secret = env('JWT_SECRET');
    }

    private function getDate()
    {
        $now = new DateTime();
        return $now->getTimestamp();
    }

    public function create($email)
    {

        $payload = array(
            "iat" => $this->getDate(),
            "user" => $email,
            "jti" => Uuid::generate()->string
        );
        $jwt = JWT::encode($payload, $this->secret);

        return $jwt;
    }
}
