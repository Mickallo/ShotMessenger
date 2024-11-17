<?php

namespace App\Infrastructure\Outbox\Entity;

use App\Infrastructure\Outbox\Repository\EventReceivedRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EventReceivedRepository::class)]
#[ORM\Table(name: "received", schema: "event")]
class EventReceived
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $receivedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getReceivedAt(): ?\DateTimeImmutable
    {
        return $this->receivedAt;
    }

    public function setReceivedAt(\DateTimeImmutable $receivedAt): static
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }
}
