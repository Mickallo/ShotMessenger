<?php

namespace App\Application\EventHandler;

use App\Application\Event\DiscussionCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'event.bus')]
final class DiscussionCreatedHandler
{
    public function __invoke(DiscussionCreated $message): void
    {
         //throw new \Exception('critical fail');
    }
}
