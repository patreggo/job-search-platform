<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;
use App\Attributes\Translatable\FilterableField;
use kvokka\SimpleDoctrineFilter\FilterType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait IsModeratedTrait
{
    #[ORM\Column]
    #[Assert\NotNull]
    #[Groups(['read'])]
    #[FilterableField(
        filterType: FilterType::Boolean,
        translatorKey: 'filter_is_moderated_field'
    )]
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