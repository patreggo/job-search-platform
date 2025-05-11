<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait IsActive
{
    #[ORM\Column]
    #[Assert\NotNull]
    #[Groups(['read'])]
    private ?bool $isActive = true;

    /**
     * @return bool|null
     */
    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }
    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
