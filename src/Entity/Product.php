<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\ProductsController;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 1000,
    operations: [
        new Get(),
        new GetCollection(),
        new Put(),
        new Post(),
        new Delete(),
        new Get(
            uriTemplate: 'multiple',
            name: 'multiple_products',
            openapiContext: [
                "summary" => "Permets d'avoir plusieurs products en fonction de leurs Id",
                'description' => "Returns a list of products based on the provided IDs",
                'parameters' => [
                    [
                        "name" => 'ids',
                        "in" => "query",
                        'schema' => [
                            "type" => "string",
                            "example" => "1,2,3"
                        ]
                    ]
                ]
            ]
        )
    ]
)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    private ?float $price = null;

    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Category $category = null;

    #[ORM\ManyToOne]
    private ?Tva $tva = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getTva(): ?Tva
    {
        return $this->tva;
    }

    public function setTva(?Tva $tva): static
    {
        $this->tva = $tva;

        return $this;
    }
}
