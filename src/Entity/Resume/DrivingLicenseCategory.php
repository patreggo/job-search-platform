<?php

namespace App\Entity\Resume;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\Resume\DrivingLicenseCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DrivingLicenseCategoryRepository::class)]
#[ApiResource(
    routePrefix: '/admin',
)]
class DrivingLicenseCategory extends AbstractResumeParameters
{
}
