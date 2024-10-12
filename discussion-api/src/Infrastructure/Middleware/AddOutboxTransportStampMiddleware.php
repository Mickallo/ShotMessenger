<?php

namespace App\Infrastructure\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

class AddOutboxTransportStampMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (!$envelope->last(TransportNamesStamp::class)) {
            $envelope = $envelope->with(new TransportNamesStamp(['outbox']));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}