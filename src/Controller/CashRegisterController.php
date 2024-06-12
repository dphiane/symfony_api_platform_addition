<?php

namespace App\Controller;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\CashRegisterJournal;
use App\Repository\CashRegisterJournalRepository;
use App\Service\JournalNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CashRegisterController extends AbstractController
{
    #[Route('/api/create_new_cash_register', name: 'app_cash_register', methods: ['POST'])]
    public function newCashRegister(Request $request,EntityManagerInterface $entityManager, JournalNumberGenerator $journalNumberGenerator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['cash_fund'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $journal = new CashRegisterJournal();
        $journal->setCashFund($data['cash_fund']);
        $journal->setCreatedAt(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));
        $numero = $journalNumberGenerator->generate();
        $journal->setNumeroJournal($numero);

        $entityManager->persist($journal);
        $entityManager->flush();
        
        return new JsonResponse(['status' => 'Cash Register created!'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/api/close_cash_register', name: 'app_cash_register_close', methods: ['POST'])]
    public function closeCashRegister(Request $request,CashRegisterJournalRepository $cashRegisterJournalRepository,EntityManagerInterface $entityManager, JournalNumberGenerator $journalNumberGenerator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['total'],$data['id'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $journal = $cashRegisterJournalRepository->findOneBy(['id' => $data['id']]);
        $journal->setTotal($data['total']);
        $journal->setClosedAt(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));

        $entityManager->persist($journal);
        $entityManager->flush();
        
        return new JsonResponse(['status' => 'Cash Register closed!'], JsonResponse::HTTP_CREATED);
    }
}
