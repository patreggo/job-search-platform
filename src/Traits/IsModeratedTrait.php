<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait IsModeratedTrait
{
    #[ORM\Column]
    #[Assert\NotNull]
    #[Groups(['read'])]
    private ?bool $isModerated = false;

    /**
     * @return bool|null
     */
    public function getIsModerated(): ?bool
    {
        return $this->isModerated;
    }

    /**
     * @param bool $isModerated
     * @return $this
     */
    public function setIsModerated(bool $isModerated): self
    {
        $this->isModerated = $isModerated;
        return $this;
    }
}