<?php
namespace App\Domain\Entity;

use App\Domain\Repository\RequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private ?Uuid $uuid = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $agentId = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $requesterId = null;

    public function setUuid(Uuid $uuid): static
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getUuid(): ?Uuid
    {
        return $this->uuid;
    }

    public function getAgentId(): ?int
    {
        return $this->agentId;
    }

    public function setAgentId(int $agentId): static
    {
        $this->agentId = $agentId;

        return $this;
    }

    public function getRequesterId(): ?int
    {
        return $this->requesterId;
    }

    public function setRequesterId(int $requesterId): static
    {
        $this->requesterId = $requesterId;

        return $this;
    }
}
