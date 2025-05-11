<?php

namespace App\Entity\Resume;

use App\Entity\Vacancy\VacancyWorkExperience;
use App\Repository\Resume\WorkPlaceRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WorkPlaceRepository::class)]
class WorkPlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: VacancyWorkExperience::class)]
    private ?VacancyWorkExperience $workExperience = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Expression(
        expression: "this.getEndDate() >= this.getStartDate() || this.getEndDate() === null",
        message: 'end_date_must_be_greater_than_start_date_or_null'
    )]
    private ?DateTimeInterface $endDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $organizationName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $professionName = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $workComposition = null;

    #[ORM\ManyToOne(targetEntity: Resume::class, cascade: ["persist", "remove"], inversedBy: 'workPlace')]
    private ?Resume $resume = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return VacancyWorkExperience|null
     */
    public function getWorkExperience(): ?VacancyWorkExperience
    {
        return $this->workExperience;
    }

    /**
     * @param VacancyWorkExperience|null $workExperience
     * @return $this
     */
    public function setWorkExperience(?VacancyWorkExperience $workExperience): static
    {
        $this->workExperience = $workExperience;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeInterface|null $startDate
     * @return $this
     */
    public function setStartDate(?DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    /**
     * @param DateTimeInterface|null $endDate
     * @return $this
     */
    public function setEndDate(?DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrganizationName(): ?string
    {
        return $this->organizationName;
    }

    /**
     * @param string|null $organizationName
     * @return $this
     */
    public function setOrganizationName(?string $organizationName): static
    {
        $this->organizationName = $organizationName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getProfessionName(): ?string
    {
        return $this->professionName;
    }

    /**
     * @param string|null $professionName
     * @return $this
     */
    public function setProfessionName(?string $professionName): static
    {
        $this->professionName = $professionName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getWorkComposition(): ?string
    {
        return $this->workComposition;
    }

    /**
     * @param string|null $workComposition
     * @return $this
     */
    public function setWorkComposition(?string $workComposition): static
    {
        $this->workComposition = $workComposition;

        return $this;
    }

    /**
     * @return Resume|null
     */
    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    /**
     * @param Resume|null $resume
     * @return $this
     */
    public function setResume(?Resume $resume): static
    {
        $this->resume = $resume;

        return $this;
    }
}
