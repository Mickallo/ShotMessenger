<?php

namespace App\Domain\Entity;

use App\Domain\Repository\DiscussionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $dossierId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossierId(): ?int
    {
        return $this->dossierId;
    }

    public function setDossierId(int $dossierId): static
    {
        $this->dossierId = $dossierId;

        return $this;
    }
}
