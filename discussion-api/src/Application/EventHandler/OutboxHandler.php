<?php

namespace App\Application\EventHandler;

use App\Application\Event\Event;
use mysql_xdevapi\Exception;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

#[AsMessageHandler(bus: 'outbox.bus')]
final class OutboxHandler
{
    public function __construct(
        private readonly MessageBusInterface $eventBus
    ){
    }

    public function __invoke(Event $message): void
    {
        //throw new \Exception('dispatch error');

        $this->eventBus->dispatch(
            $message,
            [new TransportNamesStamp('event')]
        );
    }
}
