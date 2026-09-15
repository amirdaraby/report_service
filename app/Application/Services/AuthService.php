<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function register(string $name, string $email, string $password): array
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new \DomainException(__('messages.email_taken'));
        }

        $user = $this->userRepository->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken,
        ];
    }

    public function login(string $email, string $password): array
    {
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! Hash::check($password, $user->password)) {
            throw new \DomainException(__('messages.invalid_credentials'));
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken,
        ];
    }
}
