<?php

namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\Payment;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    #[Route('/api/create_invoice', name: 'create_invoice', methods: ['POST'])]
    public function createInvoice(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['date'], $data['tva'], $data['total'], $data['products'], $data['payments'])) {
            return new JsonResponse(['status' => 'Invalid data'], JsonResponse::HTTP_BAD_REQUEST);
        }

        $invoice = new Invoice();
        $invoice->setDate(new \DateTime($data['date']));
        $invoice->setTva($data['tva']);
        $invoice->setTotal($data['total']);

        // Add products to the invoice
        foreach ($data['products'] as $productData) {
            if (!isset($productData['id'])) {
                continue;
            }
            $product = $entityManager->getRepository(Product::class)->find($productData['id']);
            if ($product) {
                $invoice->addProduct($product);
            }
        }

        // Add payments to the invoice
        $payment = new Payment();
        foreach ($data['payments'] as $paymentData) {
            if (!isset($paymentData['amount'], $paymentData['paymentMethod'])) {
                continue;
            }
            switch ($paymentData['paymentMethod']) {
                case 'Espèce':
                    $payment->setCash($paymentData['amount']);
                    break;
                case 'Carte bancaire':
                    $payment->setCreditCard($paymentData['amount']);
                    break;
                case 'Ticket restaurant':
                    $payment->setRestaurantVoucher($paymentData['amount']);
                    break;
                case 'Chèque':
                    $payment->setBankCheque($paymentData['amount']);
                    break;
                default:
                    continue;
            }
            $payment->setInvoice($invoice);
            $entityManager->persist($payment);
        }

        $entityManager->persist($invoice);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Invoice created!'], JsonResponse::HTTP_CREATED);
    }
}

