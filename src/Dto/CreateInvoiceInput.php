<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class CreateInvoiceInput
{
    #[Assert\NotBlank]
    public ?float $tva = null;

    #[Assert\NotBlank]
    public ?float $total = null;

    /**
     * @var array<InvoiceProductInput>
     */
    #[Assert\Valid]
    public array $products = [];

    /**
     * @var array<PaymentInput>
     */
    #[Assert\Valid]
    public array $payments = [];
}

class InvoiceProductInput
{
    #[Assert\NotBlank]
    public int $id;

    #[Assert\NotBlank]
    public int $quantity;
}

class PaymentInput
{
    #[Assert\NotBlank]
    public float $amount;

    #[Assert\NotBlank]
    public string $paymentMethod;
}