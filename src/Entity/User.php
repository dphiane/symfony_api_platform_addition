<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Controller\ChangePasswordController;
use App\Controller\RegistrationController;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[
    ApiResource(
        operations: [
            new Post(
                name: 'api_register',
                uriTemplate: 'register',
                controller: RegistrationController::class,
                openapiContext: [
                    "requestBody" => [
                        "required" => true,
                        'content' => [
                            "application/ld+json" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "password" => [
                                            "type" => "string",
                                            "example" => "Mot de passe",
                                            "minLength" => 6,
                                            "maxLength" => 50
                                        ],
                                        "email" => [
                                            "type" => "string",
                                            "example" => "example@example.com"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ),
            new Post(
                name: 'api_change_password',
                uriTemplate: 'change_password',
                controller: ChangePasswordController::class,
                openapiContext: [
                    "summary" => "Si l'utilisateur est connecté et souhaite changer de mot de passe",
                    "description" => "Change le mot de passe",
                    "requestBody" => [
                        "required" => true,
                        'content' => [
                            "application/ld+json" => [
                                "schema" => [
                                    "type" => "object",
                                    "properties" => [
                                        "currentPassword" => [
                                            "type" => "string",
                                            "example" => "Mot de passe actuel"
                                        ],
                                        "newPassword" => [
                                            "type" => "string",
                                            "example" => "nouveau mot de passe",
                                            "minLength" => 6,
                                            "maxLength" => 50
                                        ],
                                        "userEmail" => [
                                            "type" => "string",
                                            "example" => "example@example.com"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
            )
        ]
    )
]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Le minimum est de 6 caractères',
        maxMessage: 'Le maximum est de 50 caractères',
    )]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
