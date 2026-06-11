<?php

namespace App\DTO\Auth;

class RegisterDTO
{
    public string $name;
    public string $email;
    public string $password;
    public string $password_confirmation;

    public function __construct(
        string $name,
        string $email,
        string $password,
        string $password_confirmation
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->password_confirmation = $password_confirmation;
    }
}