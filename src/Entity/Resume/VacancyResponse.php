<?php

namespace App\Entity\Resume;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\User;
use App\Entity\Vacancy\Vacancy;
use App\Repository\Resume\VacancyResponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VacancyResponseRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
)]
#[ORM\UniqueConstraint(
    name: 'resume_id_vacancy_id',
    columns: [
        'resume_id',
        'vacancy_id'
    ]
)]
class VacancyResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'vacancyResponses')]
    #[Assert\NotNull]
    private ?Resume $resume = null;

    #[ORM\ManyToOne(inversedBy: 'vacancyResponses')]
    #[Assert\NotNull]
    private ?Vacancy $vacancy = null;

    #[ORM\ManyToOne(targetEntity: VacancyResponseStatus::class)]
    #[Assert\NotNull]
    private ?VacancyResponseStatus $status = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\Expression(
        expression: "this.getInitiator() === this.getResume().getUser() || this.getInitiator() === this.getVacancy().getCompany().getUser()",
        message: 'initiator_must_be_either_the_owner_of_the_resume_or_the_owner_of_the_vacancy'
    )]
    private ?User $initiator = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Resume|null
     */
    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    /**
     * @return string|null
     */
    public function getResumeFirstName(): ?string
    {
        return $this->resume->getFirstName();
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

    /**
     * @return Vacancy|null
     */
    public function getVacancy(): ?Vacancy
    {
        return $this->vacancy;
    }

    /**
     * @return string|null
     */
    public function getVacancyName(): ?string
    {
        return $this->vacancy->getName();
    }

    /**
     * @param Vacancy|null $vacancy
     * @return $this
     */
    public function setVacancy(?Vacancy $vacancy): static
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * @return VacancyResponseStatus|null
     */
    public function getStatus(): ?VacancyResponseStatus
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getStatusName(): ?string
    {
        return $this->status->getName();
    }

    /**
     * @param VacancyResponseStatus|null $status
     * @return $this
     */
    public function setStatus(?VacancyResponseStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getInitiator(): ?User
    {
        return $this->initiator;
    }

    /**
     * @param User|null $initiator
     * @return VacancyResponse
     */
    public function setInitiator(?User $initiator): VacancyResponse
    {
        $this->initiator = $initiator;
        return $this;
    }
}
