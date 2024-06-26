<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TvaRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ORM\Entity(repositoryClass: TvaRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
    new Put(),
    new Post(),
    new Delete(),
])]
class Tva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTva(): ?int
    {
        return $this->tva;
    }

    public function setTva(int $tva): static
    {
        $this->tva = $tva;

        return $this;
    }
}
