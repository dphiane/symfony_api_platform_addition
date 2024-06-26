<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductsController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function __invoke(Request $request):JsonResponse
    {
        $ids = $request->query->get('ids');

        if (!$ids) {
            return new JsonResponse(['error' => 'No IDs provided'], 400);
        }

        $idsArray = explode(',', $ids);
        $products = $this->productRepository->findByIds($idsArray);

        return $this->json($products); 
    }
}
