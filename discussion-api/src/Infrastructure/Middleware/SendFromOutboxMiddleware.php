<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Outbox\Stamp\OutboxStamp;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

readonly class SendFromOutboxMiddleware implements MiddlewareInterface
{
    public function __construct(
        #[Autowire(service: 'messenger.transport.event')]
        private TransportInterface $eventTransport,
        #[Autowire(service: 'messenger.transport.event_outbox_sent')]
        private TransportInterface $eventOutboxSentTransport,
    ){
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if(! $envelope->all(OutboxStamp::class)) {
            return $stack->next()->handle($envelope, $stack);
        }

        // Consume from outbox transport and sent to the event transport
        $envelope = $stack->next()->handle($envelope, $stack);

        $cleanEnvelope = new Envelope($envelope->getMessage());
        // Send to the event transport
        $this->eventTransport->send($cleanEnvelope);
        // Save in sent table
        $this->eventOutboxSentTransport->send($cleanEnvelope);

        return $envelope;
    }
}