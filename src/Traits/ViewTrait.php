<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait ViewTrait
{
    #[ORM\Column(length: 255)]
    protected ?int $view = 0;

    public function getView(): ?int
    {
        return $this->view;
    }

    public function setView(int $view): self
    {
        $this->view = $view;

        return $this;
    }
}