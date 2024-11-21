<?php

namespace App\Application\Command;

class CreateDiscussionCommand
{
    public function __construct(
        public readonly string $uuid
    ){
    }
}