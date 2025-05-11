<?php

namespace App\Entity\Resume;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Resume\VacancyResponseStatusRepository;
use App\Traits\NameTechNameTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VacancyResponseStatusRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
)]
class VacancyResponseStatus
{
    /** @var string  */
    public const DEFAULT_STATUS_TECH_NAME = "pending";

    use NameTechNameTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}
