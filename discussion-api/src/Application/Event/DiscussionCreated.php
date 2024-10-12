<?php

namespace App\Application\Event;

final class DiscussionCreated implements Event
{
     public function __construct(
         public readonly int $discussionId,
         public readonly int $dossierId,
     ) {
     }
}
