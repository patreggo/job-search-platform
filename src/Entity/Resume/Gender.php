<?php

namespace App\Entity\Resume;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Resume\GenderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GenderRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
)]
class Gender extends AbstractResumeParameters
{
}
