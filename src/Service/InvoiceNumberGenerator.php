<?php

namespace App\Service;

use App\Repository\InvoiceRepository;

class InvoiceNumberGenerator
{

    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {
    }

    public function generate(): string
    {
        $lastInvoice = $this->invoiceRepository->findOneBy([], ['id' => 'DESC']);
        $lastNumber = $lastInvoice ? (int) substr($lastInvoice->getInvoiceNumber(), -6) : 0;
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

        return date('Y') . '-' . $newNumber;
    }
}
