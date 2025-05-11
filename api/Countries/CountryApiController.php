<?php

namespace Api\Countries;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Exception;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CountryApiController
 * @package Api\Controller
 */
#[Route('/api/country')]
class CountryApiController extends AbstractFOSRestController
{

    #[Rest\Get('/list', name: 'get_country_list')]
    public function getCountryList(
        CountryRepository $countryRepository,
    ): Response {
        $countries = $countryRepository->findAll();
        return $this->handleView($this->view($countries, Response::HTTP_OK));
    }


    #[Route('/{country}/districts', name: 'get_country_districts', methods: ['GET'])]
    public function getCountryDistricts(
        Country $country,
    ): Response {
        return $this->handleView($this->view($country->getDistricts()->getIterator(), Response::HTTP_OK));
    }
}
