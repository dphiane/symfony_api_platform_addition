<?php

namespace App\DataFixtures;

use App\Entity\Tva;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TvaRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture; 

class AppFixtures extends Fixture
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private TvaRepository $tvaRepository
    ){   
    }
    public function load(ObjectManager $manager): void
    {
        // Récupérer la catégorie depuis la base de données
        $category_cold_drinks = $this->categoryRepository->findOneBy(['name' => 'Boissons Froides']);
        $category_wines = $this->categoryRepository->findOneBy(['name' => 'Vins']);
        $category_hot_beverage = $this->categoryRepository->findOneBy(['name' => 'Boissons chaudes']);
        $category_digestif = $this->categoryRepository->findOneBy(['name' => 'Digestifs']);
        $category_glass_wine = $this->categoryRepository->findOneBy(['name' => 'Vins aux verres']);
        $category_aperitif = $this->categoryRepository->findOneBy(['name' => 'Apéritifs']);

        // Récupérer la TVA depuis la base de données
        $tva_20 = $this->tvaRepository->findOneBy(['tva' => 20]);
        $tva_10 = $this->tvaRepository->findOneBy(['tva' => 10]);

        $product = new Product();
        // Charger les données depuis le fichier JSON
        $wines = json_decode(file_get_contents(__DIR__ . '/vins.json'), true);
        $aperitifs = json_decode(file_get_contents(__DIR__ .'/aperitif.json'), true);
        $hot_beverage = json_decode(file_get_contents(__DIR__ .'/boisson-chaude.json'), true);
        $cold_drinks = json_decode(file_get_contents(__DIR__ .'/boisson-froide.json'), true);
        $digestifs = json_decode(file_get_contents(__DIR__ .'/digestif.json'), true);
        $glass_wine = json_decode(file_get_contents(__DIR__ .'/vins-aux-verres.json'), true);

        foreach ($wines as $wine) {
            $product = new Product();
            $product->setName($wine['name']);
            $product->setPrice($wine['price']);
            $product->setCategory($category_wines);
            $product->setTva($tva_20);
            $manager->persist($product);
        }

        foreach ($aperitifs as $aperitif) {
            $product = new Product();
            $product->setName($aperitif['name']);
            $product->setPrice($aperitif['price']);
            $product->setCategory($category_aperitif);
            $product->setTva($tva_20);
            $manager->persist($product);
        }
        foreach ($hot_beverage as $drink) {
            $product = new Product();
            $product->setName($drink['name']);
            $product->setPrice($drink['price']);
            $product->setCategory($category_hot_beverage);
            $product->setTva($tva_10);
            $manager->persist($product);
        }
        foreach ($digestifs as $drink) {
            $product = new Product();
            $product->setName($drink['name']);
            $product->setPrice($drink['price']);
            $product->setCategory($category_digestif);
            $product->setTva($tva_20);
            $manager->persist($product);
        }
        foreach ($cold_drinks as $drink) {
            $product = new Product();
            $product->setName($drink['name']);
            $product->setPrice($drink['price']);
            $product->setCategory($category_cold_drinks);
            $product->setTva($tva_10);
            $manager->persist($product);
        }

        foreach ($glass_wine as $glass) {
            $product = new Product();
            $product->setName($glass['name']);
            $product->setPrice($glass['price']);
            $product->setCategory($category_glass_wine);
            $product->setTva($tva_20);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
