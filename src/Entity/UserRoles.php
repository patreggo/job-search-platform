<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRolesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

#[ApiResource(routePrefix: '/admin')]
#[ApiFilter(filterClass: SearchFilter::class, properties: ['name' => 'ipartial', 'displayName' => 'ipartial'])]
#[ApiFilter(filterClass: OrderFilter::class, properties: ['id'])]
#[ORM\Entity(repositoryClass: UserRolesRepository::class)]
class UserRoles
{
    /** @var string */
    public const
        ROLE_USER_AUTHOR = 'ROLE_USER_AUTHOR',
        ROLE_USER = 'ROLE_USER';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $displayName;

    #[ORM\OneToMany(mappedBy: 'roles', targetEntity: User::class)]
    private $user;

    #[ORM\Column(length: 255, nullable: true, options: ["default" => "user_index"])]
    private ?string $baseRoute = null;

    /**
     * @return PersistentCollection
     */
    public function getUser(): PersistentCollection
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setRoles($this);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRoles() === $this) {
                $user->setRoles(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return $this
     */
    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBaseRoute(): ?string
    {
        return $this->baseRoute;
    }

    /**
     * @param string|null $baseRoute
     * @return $this
     */
    public function setBaseRoute(?string $baseRoute): self
    {
        $this->baseRoute = $baseRoute;

        return $this;
    }
}
