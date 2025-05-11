<?php

namespace Api\Countries;

use App\Entity\District;
use App\Repository\DistrictRepository;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DistrictApiController
 * @package Api\Controller
 */
#[Route('/api/district')]
class DistrictApiController extends AbstractFOSRestController
{
    #[Route('/list', name: 'get_district_list', methods: ['GET'])]
    public function getDistrictList(
        DistrictRepository $districtRepository,
    ): Response {
        $districts = $districtRepository->findAll();

        return $this->handleView($this->view($districts, Response::HTTP_OK));
    }

    #[Route('/{district}/cities', name: 'get_district_cities', methods: ['GET'])]
    public function getDistrictCities(
        District $district,
    ): Response {
        return $this->handleView($this->view($district->getCities()->getIterator(), Response::HTTP_OK));
    }
}
