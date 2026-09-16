<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Application\Contracts\Repositories\UserRepository;
use App\Models\User;

final class EloquentUserRepository implements UserRepository
{
    public function __construct(
        private User $model
    ) {}

    public function create(array $data): User
    {
        return $this->model->query()->create($data);
    }

    public function existsByEmail(string $email): bool
    {
        return $this->model->query()->where('email', $email)->exists();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->query()->where('email', $email)->first();
    }
}
