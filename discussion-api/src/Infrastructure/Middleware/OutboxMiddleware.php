<?php

namespace App\Infrastructure\Middleware;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

readonly class OutboxMiddleware implements MiddlewareInterface
{
    public function __construct(
        #[Autowire(service: 'messenger.transport.event')]
        private TransportInterface $eventTransport,
        #[Autowire(service: 'messenger.transport.outbox_sent')]
        private TransportInterface $outboxSentTransport
    ){
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->last(TransportNamesStamp::class)) {
            $envelope = $envelope->with(new TransportNamesStamp(['outbox']));
        }

        $envelope = $stack->next()->handle($envelope, $stack);

        if ($envelope->last(HandledStamp::class)) {
            $noStampsEnvelope = new Envelope($envelope->getMessage());
            $this->eventTransport->send($noStampsEnvelope);
            $this->outboxSentTransport->send($noStampsEnvelope);
        }

        return $envelope;
    }
}