<?php

namespace App\Application\CommandHandler;


use App\Application\Command\CreateRequestCommand;
use App\Application\Event\RequestCreated;
use App\Domain\Entity\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler(bus: 'command.bus')]
readonly class CreateRequestCommandHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageBusInterface $eventBus
    ){
    }

    public function __invoke(CreateRequestCommand $command): void
    {
        $request = new Request();
        $request->setUuid(Uuid::v7());
        $request->setAgentId($command->agentId);
        $request->setRequesterId($command->requesterId);

        $this->entityManager->persist($request);

        $this->eventBus->dispatch(
            new RequestCreated(Uuid::v7(), $request->getUuid())
        );
    }
}