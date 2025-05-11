<?php

namespace Admin\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait PositionTrait
 * @package Admin\Traits
 */
trait PositionTrait
{
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Range(
        [
            'min' => 1
        ]
    )]
    #[Assert\Type("int")]
    #[Groups('read')]
    protected ?int $position = null;

    /**
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }
}