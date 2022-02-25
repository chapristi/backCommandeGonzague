<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\Api\CreateUser;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Api\CreateUserController;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]

#[ApiResource(
    collectionOperations: [
        "get" => [

        ],
        //"post" => [],
       
        'create_user' => [
            "pagination_enabled"  => false,
            "path" => "/users/create",
            "method" => "POST",
            "controller" => CreateUserController::class,
        ]
    ],
    itemOperations: [
        "get" => [

        ],
        "put" => [
            "security" => 'is_granted(ROLE_ADMIN)',
        ],
        "delete" => [
            "security" => 'is_granted("ROLE_ADMIN")',
        ],
        "patch" => [
            "security" => 'is_granted("ROLE_ADMIN")',
        ],
    ],
    denormalizationContext: ["groups" => ["write:User"]],
    #mercure: true,
    normalizationContext: ["groups" => ["read:User"]],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["read:User"])]

    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["read:User","write:User"])]
    private $email;

    #[ORM\Column(type: 'json')]
    #[Groups(["read:User"])]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Groups(["write:User"])]
    private $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
