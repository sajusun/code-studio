<?php

namespace App\Modules\Shared\Exceptions;

class RoomNotAvailableException extends DomainException
{
    public function __construct(int $roomId, string $message = 'Room is not available for the selected dates.')
    {
        parent::__construct("Room #{$roomId}: {$message}");
    }

    protected static function errorCode(): string
    {
        return 'room_not_available';
    }
}
