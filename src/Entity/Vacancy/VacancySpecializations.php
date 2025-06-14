<?php

namespace App\Entity\Vacancy;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Vacancy\VacancySpecializationsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: VacancySpecializationsRepository::class)]
#[ApiResource(
    routePrefix: 'admin',
    normalizationContext: ['groups' => ['read']],
    forceEager: false
)]
class VacancySpecializations extends AbstractVacancyParameters
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    protected ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups('read')]
    private ?string $name = null;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

}