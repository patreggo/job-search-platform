<?php

namespace Api\Controller\Resume;


use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ResumeParametersApiController extends AbstractFOSRestController
{
    /** @var string */
    private const RESUME_PARAMETER_NAMESPACE_PREFIX = "App\Entity\Resume\\";

    #[Rest\Get('/many_resume_parameters/{resumeParameter}', name: 'get_many_resume_parameters', methods: ['GET'])]
    public function getManyResumeParameters(
        ManagerRegistry $doctrine,
        string          $resumeParameter
    ): Response
    {
        try {
            $repository = $doctrine->getRepository(
                self::RESUME_PARAMETER_NAMESPACE_PREFIX .
                Str::asClassName($resumeParameter)
            );
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

        $parameters = $repository->findAll();
        return $this->handleView($this->view($parameters, Response::HTTP_OK));
    }

    #[Rest\Get('/single_resume_parameter/{resumeParameter}/{id}', name: 'get_single_resume_parameter', methods: ['GET'])]
    public function getSingleResumeParameters(
        string          $resumeParameter,
        int             $id,
        ManagerRegistry $doctrine
    ): Response
    {
        try {
            $repository = $doctrine->getRepository(
                self::RESUME_PARAMETER_NAMESPACE_PREFIX .
                Str::asClassName($resumeParameter)
            );
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }

        $parameter = $repository->find($id);
        return $this->handleView($this->view($parameter, Response::HTTP_OK));
    }
}
