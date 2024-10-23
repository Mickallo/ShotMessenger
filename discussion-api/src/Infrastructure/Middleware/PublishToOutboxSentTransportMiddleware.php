<?php

namespace App\Infrastructure\Middleware;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\TransportInterface;

readonly class PublishToOutboxSentTransportMiddleware implements MiddlewareInterface
{
    public function __construct(
        #[Autowire(service: 'messenger.transport.outbox_sent')]
        private TransportInterface $outboxSentTransport
    ){
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $envelope = $stack->next()->handle($envelope, $stack);

        // Vérifie si le message a été traité avec succès
        if ($envelope->last(SentStamp::class)) {
            // Envoie directement le message au transport 'outbox_sent'
            $this->outboxSentTransport->send($envelope);
        }

        return $envelope;
    }
}