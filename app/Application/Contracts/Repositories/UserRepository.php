<?php

namespace App\Application\Contracts\Repositories;

use App\Models\User;

interface UserRepository
{
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function existsByEmail(string $email): bool;
}
