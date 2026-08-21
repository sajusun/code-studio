<?php

namespace App\Modules\Shared\Exceptions;

class ReservationConflictException extends DomainException
{
    public function __construct(string $message = 'The room already has a conflicting reservation for these dates.')
    {
        parent::__construct($message);
    }

    protected static function errorCode(): string
    {
        return 'reservation_conflict';
    }
}
