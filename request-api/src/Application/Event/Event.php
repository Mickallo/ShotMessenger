<?php

namespace App\Application\Event;

interface Event
{
    public function uuid(): string;
}