<?php

namespace App\Exceptions\Report;

use App\Exceptions\ServiceException;

final class InvalidFrequencyException extends ServiceException
{
    public function __construct()
    {
        parent::__construct(__('messages.invalid_frequency'));
    }
}
