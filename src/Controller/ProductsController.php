<?php

namespace App\Controller;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[ApiResource()]
class ProductsController extends AbstractController
{
    #[Route('api/multiple', name: 'multiple_products',methods:'GET')]
    public function getProducts(Request $request,ProductRepository $productRepository):JsonResponse
    {
        $ids = $request->query->get('ids');

        if (!$ids) {
            return new JsonResponse(['error' => 'No IDs provided'], 400);
        }

        $idsArray = explode(',', $ids);
        $products = $productRepository->findByIds($idsArray);

        return $this->json($products); 
    }
}
