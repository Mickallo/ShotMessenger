<?php

namespace App\Application\Event;

final class DiscussionCreated implements Event
{
     public function __construct(
         public readonly string $uuid,
         public readonly string $discussionUuid,
     ) {
     }

    public function uuid(): string
    {
        return $this->uuid;
    }
}
