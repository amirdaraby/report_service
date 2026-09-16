<?php

namespace App\Application\Services;

use App\Application\Contracts\Repositories\UserRepository;
use App\Exceptions\Auth\EmailTakenException;
use App\Exceptions\Auth\InvalidCredentialException;
use App\Exceptions\Auth\LogoutFailedException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    public function register(string $name, string $email, string $password): array
    {
        if ($this->userRepository->existsByEmail($email)) {
            throw new EmailTakenException();
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
            throw new InvalidCredentialException();
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth-token')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $deleted = $user->currentAccessToken()->delete();

        if (! $deleted) {
            throw new LogoutFailedException();
        }
    }
}
