<?php

namespace Api\Countries;

use App\Entity\City;
use App\Repository\CityRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CityApiController
 * @package Api\Controller
 */
#[Route('/api/city')]
class CityApiController extends AbstractFOSRestController
{
    #[Rest\Get('/list', name: 'get_city_list', methods: ['GET'])]
    public function getCityList(
        CityRepository $cityRepository,
    ): Response {
        $cities = $cityRepository->findAll();
        return $this->handleView($this->view($cities, Response::HTTP_OK));
    }

    #[Rest\Get('/{id}', name: 'get_single_city', methods: ['GET'])]
    public function getSingleCity(
        City $city,
        CityRepository $cityRepository,
    ): Response
    {
        $data = $cityRepository->find(['id' => $city->getId()]);
        return $this->handleView($this->view($data, Response::HTTP_OK));
    }
}
