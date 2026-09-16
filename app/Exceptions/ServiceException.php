<?php

namespace App\Exceptions;

use RuntimeException;

final class ServiceException extends RuntimeException
{
    public function __construct(string $message = '', int $code = 400)
    {
        parent::__construct($message, $code);
    }
}
