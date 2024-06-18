<?php

namespace App\Entity;

use App\Repository\PasswordTokenRepository;
use CoopTilleuls\ForgotPasswordBundle\Entity\AbstractPasswordToken;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasswordTokenRepository::class)]
class PasswordToken extends AbstractPasswordToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}
