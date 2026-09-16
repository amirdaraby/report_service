<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ServiceException;

final class LogoutFailedException extends ServiceException
{
    public function __construct()
    {
        parent::__construct(__('messages.logout_failed'));
    }
}
