<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ServiceException;
use Symfony\Component\HttpFoundation\Response;

final class InvalidCredentialException extends ServiceException
{
    public function __construct()
    {
        parent::__construct(__('messages.invalid_credentials'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
