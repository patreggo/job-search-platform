<?php

namespace App\Entity\Vacancy;


use App\Traits\PositionTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractVacancyParameters
{
    use PositionTrait;

    /** @var string[] */
    public const VACANCY_PARAMETERS_LIST = [
        'communication_type',
        'company_industry',
        'education',
        'income_payment',
        'income_payment_period',
        'employment_type',
        'interaction_languages',
        'key_skills',
        'relocation',
        'specializations',
        'work_experience',
        'work_schedule',
        'registration_methods'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    protected ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    #[Assert\NotNull]
    protected ?string $techName = null;

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
    public function getTechName(): ?string
    {
        return $this->techName;
    }

    /**
     * @param string|null $techName
     * @return $this
     */
    public function setTechName(?string $techName): self
    {
        $this->techName = $techName;

        return $this;
    }
}