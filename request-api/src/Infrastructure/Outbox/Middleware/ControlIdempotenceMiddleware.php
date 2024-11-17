<?php

namespace App\Infrastructure\Outbox\Middleware;

use App\Application\Event\Event;
use App\Infrastructure\Outbox\Entity\EventReceived;
use App\Infrastructure\Outbox\Repository\EventReceivedRepository;
use App\Infrastructure\Outbox\Stamp\OutboxStamp;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Uid\Uuid;

readonly class ControlIdempotenceMiddleware implements MiddlewareInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EventReceivedRepository $eventReceivedRepository
    ){
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message=$envelope->getMessage();
        if( $envelope->all(ReceivedStamp::class)
            && empty($envelope->all(OutboxStamp::class))
            && $message instanceof Event
        ) {

            if($this->eventReceivedRepository->findBy(['uuid'=>$message->uuid()])){
                throw new UnrecoverableMessageHandlingException('message already processed');
            }

            $envelope = $stack->next()->handle($envelope, $stack);

            $this->entityManager->persist(
                (new EventReceived())
                    ->setUuid(Uuid::fromString($message->uuid()))
                    ->setReceivedAt(new \DateTimeImmutable())
            );

            return $envelope;
        }

        // Consume from the event transport
        return $stack->next()->handle($envelope, $stack);
    }
}