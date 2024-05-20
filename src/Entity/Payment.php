<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ApiResource]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $cash = null;

    #[ORM\Column(nullable: true)]
    private ?float $creditCard = null;

    #[ORM\Column(nullable: true)]
    private ?float $restaurantVoucher = null;

    #[ORM\ManyToOne(inversedBy: 'payment')]
    private ?Invoice $invoice = null;

    #[ORM\Column(nullable:true)]
    private ?float $bankCheque = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCash(): ?float
    {
        return $this->cash;
    }

    public function setCash(float $cash): static
    {
        $this->cash = $cash;

        return $this;
    }

    public function getCreditCard(): ?float
    {
        return $this->creditCard;
    }

    public function setCreditCard(?float $creditCard): static
    {
        $this->creditCard = $creditCard;

        return $this;
    }

    public function getRestaurantVoucher(): ?float
    {
        return $this->restaurantVoucher;
    }

    public function setRestaurantVoucher(?float $restaurantVoucher): static
    {
        $this->restaurantVoucher = $restaurantVoucher;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getBankCheque(): ?float
    {
        return $this->bankCheque;
    }

    public function setBankCheque(float $bankCheque): static
    {
        $this->bankCheque = $bankCheque;

        return $this;
    }
}
