<?php

namespace App\Application\Command;

readonly class CreateRequestCommand
{
    public function __construct(
        public int $agentId,
        public int $requesterId
    ){
    }
}