<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Money\Currency;
use App\Entity\Resume\Resume;
use App\Entity\Vacancy\Vacancy;
use App\Repository\UserRepository;
use App\Traits\IsEnabledTrait;
use App\Traits\TimestampTrait;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    security: 'is_granted("ROLE_CONTENT_MANAGER")'
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'email'])]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampTrait, IsEnabledTrait;

    public function __construct()
    {
        $this->isEnabled = true;
        $this->resumes = new ArrayCollection();
        $this->vacancyFavorites = new ArrayCollection();
        $this->resumeFavorites = new ArrayCollection();
        $this->favoriteUsers = new ArrayCollection();
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups('read')]
    #[Assert\NotNull]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\ManyToOne(targetEntity: UserRoles::class, inversedBy: 'user')]
    #[ORM\JoinColumn(name: 'role_id', nullable: false)]
    private $roles;

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[Assert\File(
        maxSize: '2192k',
        mimeTypes: [
            'image/jpg',
            'image/jpeg',
            'image/png'
        ]
    )]
    #[Assert\Image(
        maxWidth: 1280,
        maxHeight: 720,
    )]
    private $avatarFile = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastAuthAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $activated = null;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    private ?Country $country = null;

    #[ORM\ManyToOne(targetEntity: City::class)]
    private ?City $city = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Currency $currency = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Company::class)]
    private Collection $companies;

    #[ORM\ManyToMany(targetEntity: Vacancy::class, inversedBy: 'favoritesUsers')]
    private Collection $vacancyFavorites;

    #[ORM\ManyToMany(targetEntity: Resume::class, inversedBy: 'favoritesUsers')]
    private Collection $resumeFavorites;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Resume::class)]
    private Collection $resumes;

    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'favoritesBy', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'favorites_users')]
    private Collection $favoriteUsers;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'favoriteUsers')]
    private Collection $favoritesBy;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return $this
     */
    public function setEmail(?string $email): self
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
        return (string)$this->email;
    }

    /**
     * @return UserRoles
     */
    public function getRoleEntity(): UserRoles
    {
        return $this->roles;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        if ($this->roles != null) {
            return [$this->roles->getName()];
        }

        return [];
    }

    /**
     * @param UserRoles|null $roles
     * @return $this
     */
    public function setRoles(
        ?UserRoles $roles
    ): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    #[Groups('read')]
    public function getRoleName()
    {
        return $this->roles->getDisplayName();
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
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

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    /**
     * @param string|null $LastName
     * @return $this
     */
    public function setLastName(?string $LastName): self
    {
        $this->LastName = $LastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     * @return $this
     */
    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getLastAuthAt(): ?DateTimeImmutable
    {
        return $this->lastAuthAt;
    }

    /**
     * @param DateTimeImmutable|null $lastAuthAt
     * @return $this
     */
    public function setLastAuthAt(?DateTimeImmutable $lastAuthAt): self
    {
        $this->lastAuthAt = $lastAuthAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isActivated(): ?bool
    {
        return $this->activated;
    }

    /**
     * @param bool|null $activated
     * @return $this
     */
    public function setActivated(?bool $activated): self
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * @param null $avatarFile
     */
    public function setAvatarFile($avatarFile = null): void
    {
        $this->avatarFile = $avatarFile;

        if (null !== $avatarFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @return null
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     * @return $this
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     * @return $this
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }


    /**
     * @return Collection<int, Company>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    /**
     * @param Company $company
     * @return $this
     */
    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setUser($this);
        }

        return $this;
    }

    /**
     * @param Company $company
     * @return $this
     */
    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getUser() === $this) {
                $company->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Currency|null
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency|null $currency
     * @return $this
     */
    public function setCurrency(?Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, Vacancy>
     */
    public function getVacancyFavorites(): Collection
    {
        return $this->vacancyFavorites;
    }

    /**
     * @param Vacancy $vacancyFavorite
     * @return $this
     */
    public function addVacancyFavorite(Vacancy $vacancyFavorite): self
    {
        if (!$this->vacancyFavorites->contains($vacancyFavorite)) {
            $this->vacancyFavorites->add($vacancyFavorite);
        }

        return $this;
    }

    /**
     * @param Vacancy $vacancyFavorite
     * @return $this
     */
    public function removeVacancyFavorite(Vacancy $vacancyFavorite): self
    {
        $this->vacancyFavorites->removeElement($vacancyFavorite);

        return $this;
    }

    /**
     * @return Collection<int, Resume>
     */
    public function getResumeFavorites(): Collection
    {
        return $this->resumeFavorites;
    }

    /**
     * @param Resume $resumeFavorite
     * @return $this
     */
    public function addResumeFavorite(Resume $resumeFavorite): self
    {
        if (!$this->resumeFavorites->contains($resumeFavorite)) {
            $this->resumeFavorites->add($resumeFavorite);
        }

        return $this;
    }

    /**
     * @param Resume $resumeFavorite
     * @return $this
     */
    public function removeResumeFavorite(Resume $resumeFavorite): self
    {
        $this->resumeFavorites->removeElement($resumeFavorite);

        return $this;
    }

    /**
     * @return Collection<int, Resume>
     */
    public function getResumes(): Collection
    {
        return $this->resumes;
    }

    /**
     * @param Resume $resume
     * @return $this
     */
    public function addResume(Resume $resume): static
    {
        if (!$this->resumes->contains($resume)) {
            $this->resumes->add($resume);
            $resume->setUser($this);
        }

        return $this;
    }

    /**
     * @param Resume $resume
     * @return $this
     */
    public function removeResume(Resume $resume): static
    {
        if ($this->resumes->removeElement($resume)) {
            // set the owning side to null (unless already changed)
            if ($resume->getUser() === $this) {
                $resume->setUser(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, self>
     */
    public function getFavoriteUsers(): Collection
    {
        return $this->favoriteUsers;
    }

    public function addFavoriteUser(self $userFavorite): static
    {
        if (!$this->favoriteUsers->contains($userFavorite)) {
            $this->favoriteUsers->add($userFavorite);
        }

        return $this;
    }

    public function removeUserFavorite(self $userFavorite): static
    {
        $this->favoriteUsers->removeElement($userFavorite);

        return $this;
    }
}
