<?php

namespace App\Application\Event;

final class RequestCreated implements Event
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $requestUuid
    ) {
    }

    public function uuid(): string
    {
        return $this->uuid;
    }
}