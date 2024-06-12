<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CashRegisterJournalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CashRegisterJournalRepository::class)]
#[ApiResource]
class CashRegisterJournal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $cashFund = null;

    #[ORM\Column(nullable:true)]
    private ?float $total = null;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'cashRegisterJournal')]
    private Collection $invoices;

    #[ORM\Column(length: 255)]
    private ?string $numeroJournal = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $closed_at = null;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCashFund(): ?float
    {
        return $this->cashFund;
    }

    public function setCashFund(float $cashFund): static
    {
        $this->cashFund = $cashFund;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setCashRegisterJournal($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getCashRegisterJournal() === $this) {
                $invoice->setCashRegisterJournal(null);
            }
        }

        return $this;
    }

    public function getNumeroJournal(): ?string
    {
        return $this->numeroJournal;
    }

    public function setNumeroJournal(string $numeroJournal): static
    {
        $this->numeroJournal = $numeroJournal;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closed_at;
    }

    public function setClosedAt(?\DateTimeImmutable $closed_at): static
    {
        $this->closed_at = $closed_at;

        return $this;
    }
}
