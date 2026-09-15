<?php

namespace App\Infrastructure\Eloquent\Repositories;

use App\Application\Contracts\Repositories\UserRepository;
use App\Models\User;

class EloquentUserRepository implements UserRepository
{
    public function __construct(
        private User $model
    ) {}

    public function create($data): User
    {
        return $this->model->newQuery()->create($data);
    }
}
