<?php

namespace App\Entity;

use App\Traits\PositionTrait;
use ApiPlatform\Metadata\ApiResource;
use App\Entity\Vacancy\Vacancy;
use App\Repository\CountryRepository;
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
#[UniqueEntity(fields: ['techName'], message: 'unique_tech_name_country')]
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    use PositionTrait;
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

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: District::class)]
    private Collection $districts;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Vacancy::class)]
    private Collection $vacancies;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
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
     * @return Collection<int, District>
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    /**
     * @param District $district
     * @return $this
     */
    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts->add($district);
            $district->setCountry($this);
        }

        return $this;
    }

    /**
     * @param District $district
     * @return $this
     */
    public function removeDistrict(District $district): self
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getCountry() === $this) {
                $district->setCountry(null);
            }
        }

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
            $vacancy->setCountry($this);
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
            // set the owning side to null (unless already changed)
            if ($vacancy->getCountry() === $this) {
                $vacancy->setCountry(null);
            }
        }

        return $this;
    }
}
