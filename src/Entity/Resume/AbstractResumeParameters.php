<?php

namespace App\Entity\Resume;

use App\Traits\NameTechNameTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(
    fields: ['techName']
)]
abstract class AbstractResumeParameters
{
    use NameTechNameTrait;

    /** @var string[] */
    public const RESUME_PARAMETERS_LIST = [
        'gender',
        'driving_license_category'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    protected ?int $id = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }
}