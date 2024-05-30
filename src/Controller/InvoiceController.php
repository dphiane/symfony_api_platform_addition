<?php

namespace App\Controller;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Entity\InvoiceProducts;
use App\Entity\Payment;
use App\Entity\Product;
use App\Service\InvoiceNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    #[Route('/api/create_invoice', name: 'create_invoice', methods: ['POST'])]
    public function createInvoice(Request $request, EntityManagerInterface $entityManager,InvoiceNumberGenerator $invoiceNumberGenerator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['tva'], $data['total'], $data['products'], $data['payments'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $invoice = new Invoice();
        $invoice->setDate(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));
        $invoice->setTva($data['tva']);
        $invoice->setTotal($data['total']);
        $invoice->setInvoiceNumber($invoiceNumberGenerator->generate());
        // Add products to the invoice
        foreach ($data['products'] as $productData) {
            if (!isset($productData['id'], $productData['quantity'])) {
                continue;
            }
            $product = $entityManager->getRepository(Product::class)->find($productData['id']);
            if ($product) {
                $invoiceProduct = new InvoiceProducts();
                $invoiceProduct->setProduct($product);
                $invoiceProduct->setQuantity($productData['quantity']);
                $invoice->addInvoiceProduct($invoiceProduct);
                $entityManager->persist($invoiceProduct);
            }
        }

        // Add payments to the invoice
        $payments = $data['payments'];
        
        foreach ($payments as $paymentData) {
            $payment = new Payment();
            $payment->setAmount($paymentData['amount']);
            $payment->setPaymentMethod($paymentData['paymentMethod']);
            $payment->setInvoice($invoice); // Associe la facture à ce paiement
            $entityManager->persist($payment);
            $payment->setInvoice($invoice);
            $entityManager->persist($payment);
        }

        $entityManager->persist($invoice);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Invoice created!'], JsonResponse::HTTP_CREATED);
    }
}
