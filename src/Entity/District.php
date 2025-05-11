<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DistrictRepository;
use App\Traits\TranslatorNameTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    routePrefix: '/admin',
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[UniqueEntity(fields: ['techName'], message: 'unique_tech_name_district')]
#[ORM\Entity(repositoryClass: DistrictRepository::class)]
class District
{
    use TranslatorNameTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups('read')]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    #[Groups('read')]
    private ?string $techName = null;

    #[ORM\ManyToOne(inversedBy: 'districts')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Assert\NotNull]
    private ?Country $country = null;

    #[ORM\OneToMany(mappedBy: 'district', targetEntity: City::class)]
    private Collection $cities;

    #[ORM\Column(nullable: true)]
    private ?array $importPlaceType = null;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
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
     * @return string|null
     */
    public function getTechName(): ?string
    {
        return $this->techName;
    }

    /**
     * @param string $techName
     * @return $this
     */
    public function setTechName(string $techName): self
    {
        $this->techName = $techName;

        return $this;
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

    #[Groups('read')]
    public function getCountryName()
    {
        return $this->country ? $this->country->getName() : '-';
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
            $city->setDistrict($this);
        }

        return $this;
    }

    /**
     * @param City $city
     * @return $this
     */
    public function removeCity(City $city): self
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getDistrict() === $this) {
                $city->setDistrict(null);
            }
        }

        return $this;
    }

    public function getImportPlaceType(): ?array
    {
        return $this->importPlaceType;
    }

    public function setImportPlaceType(?array $importPlaceType): static
    {
        $this->importPlaceType = $importPlaceType;

        return $this;
    }
}
