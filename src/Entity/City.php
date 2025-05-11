<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Vacancy\Vacancy;
use App\Repository\CityRepository;
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
#[UniqueEntity(fields: ['techName'], message: 'unique_tech_name_city')]
#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
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

    #[ORM\ManyToOne(inversedBy: 'cities')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[Assert\NotNull]
    private ?District $district = null;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: Vacancy::class)]
    private Collection $vacancies;


    public function __construct()
    {
        $this->vacancies = new ArrayCollection();
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
     * @return District|null
     */
    public function getDistrict(): ?District
    {
        return $this->district;
    }

    /**
     * @param District|null $district
     * @return $this
     */
    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    /**
     * @return Collection<int, Vacancy>
     */
    public function getVacancies(): Collection
    {
        return $this->vacancies;
    }

    /**
     * @param Vacancy $vacancy
     * @return $this
     */
    public function addVacancy(Vacancy $vacancy): self
    {
        if (!$this->vacancies->contains($vacancy)) {
            $this->vacancies->add($vacancy);
            $vacancy->setCity($this);
        }

        return $this;
    }

    /**
     * @param Vacancy $vacancy
     * @return $this
     */
    public function removeVacancy(Vacancy $vacancy): self
    {
        if ($this->vacancies->removeElement($vacancy)) {
            if ($vacancy->getCity() === $this) {
                $vacancy->setCity(null);
            }
        }

        return $this;
    }
}
