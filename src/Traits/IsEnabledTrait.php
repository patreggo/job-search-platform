<?php

namespace App\Traits;

use App\Attributes\Translatable\FilterableField;
use Doctrine\ORM\Mapping as ORM;
use kvokka\SimpleDoctrineFilter\FilterType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

trait IsEnabledTrait
{
    #[ORM\Column]
    #[Assert\NotNull]
    #[Groups(['read'])]
    #[FilterableField(
        filterType: FilterType::Boolean,
        translatorKey: 'filter_is_enabled_field'
    )]
    private ?bool $isEnabled = false;

    /**
     * @return bool|null
     */
    public function isIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }
    /**
     * @param bool $isEnabled
     * @return $this
     */
    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }
}