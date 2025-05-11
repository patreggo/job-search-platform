<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait TranslatorNameTrait
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('read')]
    private ?string $nationalName = null;

    /**
     * @return string|null
     */
    public function getNationalName(): ?string
    {
        return $this->nationalName;
    }

    /**
     * @param string|null $nationalName
     */
    public function setNationalName(?string $nationalName): void
    {
        $this->nationalName = $nationalName;
    }
}