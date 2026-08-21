<?php

namespace App\Modules\Shared\Exceptions;

class InvalidStayStateException extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    protected static function errorCode(): string
    {
        return 'invalid_stay_state';
    }
}
