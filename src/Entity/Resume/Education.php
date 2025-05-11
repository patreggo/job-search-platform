<?php

namespace App\Entity\Resume;

use App\Entity\Vacancy\VacancyEducation;
use App\Repository\Resume\EducationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EducationRepository::class)]
class Education
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: VacancyEducation::class)]
    #[Assert\NotNull]
    private ?VacancyEducation $levelEducation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $university = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $speciality = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $faculty = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Assert\NotNull]
    private ?int $graduationYear = null;

    #[ORM\ManyToOne(targetEntity: Resume::class, cascade: ["persist", "remove"], inversedBy: 'education')]
    private ?Resume $resume = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return VacancyEducation|null
     */
    public function getLevelEducation(): ?VacancyEducation
    {
        return $this->levelEducation;
    }

    /**
     * @param VacancyEducation|null $levelEducation
     * @return $this
     */
    public function setLevelEducation(?VacancyEducation $levelEducation): static
    {
        $this->levelEducation = $levelEducation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUniversity(): ?string
    {
        return $this->university;
    }

    /**
     * @param string|null $university
     * @return $this
     */
    public function setUniversity(?string $university): static
    {
        $this->university = $university;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    /**
     * @param string|null $speciality
     * @return $this
     */
    public function setSpeciality(?string $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFaculty(): ?string
    {
        return $this->faculty;
    }

    /**
     * @param string|null $faculty
     * @return $this
     */
    public function setFaculty(?string $faculty): static
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getGraduationYear(): ?int
    {
        return $this->graduationYear;
    }

    /**
     * @param int|null $graduationYear
     * @return $this
     */
    public function setGraduationYear(?int $graduationYear): static
    {
        $this->graduationYear = $graduationYear;

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
