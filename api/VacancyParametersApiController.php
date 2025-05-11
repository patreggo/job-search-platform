<?php

namespace Api;

use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api')]
class VacancyParametersApiController extends AbstractFOSRestController
{
    /** @var string */
    private const VACANCY_PARAMETER_NAMESPACE_PREFIX = "App\Entity\Vacancy\Vacancy";

    #[Rest\Get('/many_vacancy_parameters/{vacancyParameter}', name: 'get_many_vacancy_parameters', methods: ['GET'])]
    public function getManyVacancyParameters(
        string $vacancyParameter,
        ManagerRegistry $doctrine
    ): Response {
        try {
            $repository = $doctrine->getRepository(
                self::VACANCY_PARAMETER_NAMESPACE_PREFIX .
                Str::asClassName($vacancyParameter)
            );
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

        $parameters = $repository->findAll();
        return $this->handleView($this->view($parameters, Response::HTTP_OK));
    }


    #[Route('/single_vacancy_parameter/{vacancyParameter}/{id}', name: 'get_single_vacancy_parameter', methods: ['GET'])]
    public function getSingleVacancyParameters(
        string $vacancyParameter,
        int $id,
        ManagerRegistry $doctrine
    ): Response {
        try {
            $repository = $doctrine->getRepository(
                self::VACANCY_PARAMETER_NAMESPACE_PREFIX .
                Str::asClassName($vacancyParameter)
            );
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

        $parameter = $repository->find($id);
        return $this->handleView($this->view($parameter, Response::HTTP_OK));
    }
}
