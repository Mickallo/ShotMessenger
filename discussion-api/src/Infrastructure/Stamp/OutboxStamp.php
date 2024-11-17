<?php

namespace App\Infrastructure\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

final class OutboxStamp implements StampInterface
{
    public function __construct()
    {
    }
}
