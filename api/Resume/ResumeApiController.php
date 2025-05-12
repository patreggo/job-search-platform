<?php

namespace Api\Resume;

use App\Entity\Resume\Resume;
use App\Repository\Resume\ResumeRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api/resume', name: 'api_resume_')]
class ResumeApiController extends AbstractFOSRestController
{

    #[Rest\Get('/list', name: 'list', methods: ['GET'])]
    public function getManyResumes(
        ResumeRepository $resumeRepository,
    ): Response
    {
        $resumes = $resumeRepository->findAll();
        return $this->handleView($this->view($resumes, Response::HTTP_OK));
    }


    #[Rest\Get('/{id}', name: 'single', methods: ['GET'])]
    public function getSingleResume(
        Resume           $resume,
        ResumeRepository $resumeRepository,
    ): Response
    {
        $data = $resumeRepository->find(['id' => $resume->getId()]);
        return $this->handleView($this->view($data, Response::HTTP_OK));
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function newResume(
        Request              $request,
    ): Response
    {
        $resume = new Resume();
        $resume->setUser($this->getUser());


    }
}