<?php

namespace App\Infrastructure\Middleware;

use App\Infrastructure\Outbox\Stamp\OutboxStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

readonly class AddOutboxStampMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        // Message was just Publish, we force transport to outbox
        if(!$envelope->all(ReceivedStamp::class)) {
            $envelope = $envelope->with(
                new OutboxStamp(),
                new TransportNamesStamp('event_outbox'));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}