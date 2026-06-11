<?php

namespace App\DTO\Auth;

class AuthResponseDTO
{
    public int $id;
    public string $name;
    public string $email;
    public string $token;

    public function __construct(
        int $id,
        string $name,
        string $email,
        string $token
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }
}