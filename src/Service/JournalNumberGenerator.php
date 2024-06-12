<?php

namespace App\Service;

use App\Repository\CashRegisterJournalRepository;

class JournalNumberGenerator
{

    public function __construct(
        private CashRegisterJournalRepository $cashRegisterJournalRepository
    ) {
    }

    public function generate(): string
    {
        $lastInvoice = $this->cashRegisterJournalRepository->findOneBy([], ['id' => 'DESC']);
        $lastNumber = $lastInvoice ? (int) substr($lastInvoice->getNumeroJournal(), -6) : 0;
        $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);

        return date('Y-m') . '-' . $newNumber;
    }
}
