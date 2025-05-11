<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait NameTechNameTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    #[Assert\NotNull]
    protected ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    #[Assert\NotNull]
    protected ?string $techName = null;

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
    public function setName(?string $name): self
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
     * @param string|null $techName
     * @return $this
     */
    public function setTechName(?string $techName): self
    {
        $this->techName = $techName;

        return $this;
    }
}