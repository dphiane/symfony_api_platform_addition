<?php

namespace App\Controller;

use DateTimeZone;
use DateTimeImmutable;
use App\Entity\Invoice;
use App\Entity\Payment;
use App\Entity\Product;
use App\Dto\CreateInvoiceInput;
use App\Entity\InvoiceProducts;
use App\Service\InvoiceNumberGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private InvoiceNumberGenerator $invoiceNumberGenerator,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator // Injection du ValidatorInterface
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        // Désérialisation des données JSON en utilisant le DTO
        $createInvoiceInput = $this->serializer->deserialize($request->getContent(), CreateInvoiceInput::class, 'json');
        dump($createInvoiceInput);
        // Validation du DTO
        $violations = $this->validator->validate($createInvoiceInput);
        if (count($violations) > 0) {
            $errorMessages = [];
            foreach ($violations as $violation) {
                $errorMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return new JsonResponse(['status' => 'Invalid data', 'errors' => $errorMessages], JsonResponse::HTTP_BAD_REQUEST);
        }

        $invoice = new Invoice();
        $invoice->setDate(new DateTimeImmutable("now", new DateTimeZone('Europe/Paris')));
        $invoice->setTva($createInvoiceInput->tva);
        $invoice->setTotal($createInvoiceInput->total);
        $invoice->setInvoiceNumber($this->invoiceNumberGenerator->generate());

        // Ajout des produits à la facture
        foreach ($createInvoiceInput->products as $productInput) {
            $product = $this->entityManager->getRepository(Product::class)->find($productInput->id);

            if (!$product) {
                return new JsonResponse(['status' => 'Product not found for ID: ' . $productInput->id], JsonResponse::HTTP_BAD_REQUEST);
            }

            $invoiceProduct = new InvoiceProducts();
            $invoiceProduct->setProduct($product);
            $invoiceProduct->setQuantity($productInput->quantity);
            $invoice->addInvoiceProduct($invoiceProduct);
            $this->entityManager->persist($invoiceProduct);
        }

        // Ajout des paiements à la facture
        foreach ($createInvoiceInput->payments as $paymentInput) {
            $payment = new Payment();
            $payment->setAmount($paymentInput->amount);
            $payment->setPaymentMethod($paymentInput->paymentMethod);
            $payment->setInvoice($invoice);
            $this->entityManager->persist($payment);
        }

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Invoice created!'], JsonResponse::HTTP_CREATED);
    }
}
