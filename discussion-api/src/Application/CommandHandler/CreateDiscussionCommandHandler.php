<?php

namespace App\Application\CommandHandler;


use App\Application\Command\CreateDiscussionCommand;
use App\Application\Event\DiscussionCreated;
use App\Domain\Entity\Discussion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Uid\Uuid;

#[AsMessageHandler(bus: 'command.bus')]
class CreateDiscussionCommandHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $eventBus
    ){
    }

    public function __invoke(CreateDiscussionCommand $command): void
    {
        $discussion = new Discussion();
        $discussion->setDossierId($command->id);
        $this->entityManager->persist($discussion);

        $this->eventBus->dispatch(
            new DiscussionCreated(Uuid::v4(), $discussion->getId(),$discussion->getDossierId())
        );
    }
}