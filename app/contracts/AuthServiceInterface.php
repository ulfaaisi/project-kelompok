<?php

namespace App\Contracts;

interface AuthServiceInterface
{
    public function attempt(array $credentials, bool $remember = false): bool;

    public function register(array $data): bool;

    public function logout(): void;
}