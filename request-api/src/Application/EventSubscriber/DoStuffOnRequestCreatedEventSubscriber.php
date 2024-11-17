<?php

namespace App\Application\EventSubscriber;

use App\Application\Event\RequestCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler(bus: 'event.bus')]
class DoStuffOnRequestCreatedEventSubscriber
{

    public function __invoke(RequestCreated $event): void
    {
        //do nothing
    }
}