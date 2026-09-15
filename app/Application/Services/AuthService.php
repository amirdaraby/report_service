<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\UserRepository;
use App\Models\User;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function register(string $email, string $password, string $name) {}

    public function login(string $email, string $password){}
}
