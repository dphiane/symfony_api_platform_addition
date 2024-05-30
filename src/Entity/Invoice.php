<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ApiResource]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeInterface $date = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column]
    private ?float $total = null;

    /**
     * @var Collection<int, Payment>
     */
    #[ORM\OneToMany(targetEntity: Payment::class, mappedBy: 'invoice',  cascade: ['persist', 'remove'])]
    private Collection $payment;

    #[ORM\Column(length: 255)]
    private ?string $InvoiceNumber = null;

    /**
     * @var Collection<int, InvoiceProducts>
     */
    #[ORM\OneToMany(targetEntity: InvoiceProducts::class, mappedBy: 'invoice', orphanRemoval: true)]
    private Collection $invoiceProducts;

    public function __construct()
    {
        $this->payment = new ArrayCollection();
        $this->invoiceProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayment(): Collection
    {
        return $this->payment;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payment->contains($payment)) {
            $this->payment->add($payment);
            $payment->setInvoice($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payment->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getInvoice() === $this) {
                $payment->setInvoice(null);
            }
        }

        return $this;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->InvoiceNumber;
    }

    public function setInvoiceNumber(string $InvoiceNumber): static
    {
        $this->InvoiceNumber = $InvoiceNumber;

        return $this;
    }

    /**
     * @return Collection<int, InvoiceProducts>
     */
    public function getInvoiceProducts(): Collection
    {
        return $this->invoiceProducts;
    }

    public function addInvoiceProduct(InvoiceProducts $invoiceProduct): static
    {
        if (!$this->invoiceProducts->contains($invoiceProduct)) {
            $this->invoiceProducts->add($invoiceProduct);
            $invoiceProduct->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceProduct(InvoiceProducts $invoiceProduct): static
    {
        if ($this->invoiceProducts->removeElement($invoiceProduct)) {
            // set the owning side to null (unless already changed)
            if ($invoiceProduct->getInvoice() === $this) {
                $invoiceProduct->setInvoice(null);
            }
        }

        return $this;
    }
}
