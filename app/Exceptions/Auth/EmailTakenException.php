<?php

namespace App\Exceptions\Auth;

use App\Exceptions\ServiceException;
use Symfony\Component\HttpFoundation\Response;

class EmailTakenException extends ServiceException
{
    public function __construct()
    {
        parent::__construct(__('messages.email_taken'), Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
