<?php

namespace App\Application\EventHandler;

use App\Application\Command\CreateDiscussionCommand;
use App\Application\Event\RequestCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler(bus: 'event.bus')]
final readonly class RequestCreatedHandler
{
    public function __construct(
        private MessageBusInterface $commandBus
    ){

    }

    public function __invoke(RequestCreated $event): void
    {
        $this->commandBus->dispatch(
            new CreateDiscussionCommand(
                $event->requestUuid
            )
        );
    }
}
