<?php

namespace Api;

use App\Entity\Vacancy\Vacancy;
use App\Form\ApiVacancyType;
use App\Repository\Vacancy\VacancyRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class VacancyApiController extends AbstractFOSRestController
{
    #[Rest\Get('/many_vacancies', name: 'get_many_vacancies')]
    public function getManyVacancies(
        VacancyRepository $vacancyRepository,
    ): Response
    {
        $vacancies = $vacancyRepository->findAll();
        return $this->handleView($this->view($vacancies, Response::HTTP_OK));
    }

    #[Rest\Post('/new_vacancy', name: 'create_new_vacancy')]
    public function newVacancy(
        Request                $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $vacancy = new Vacancy();
        $form = $this->createForm(ApiVacancyType::class, $vacancy);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($vacancy);
            $entityManager->flush();
            return $this->handleView($this->view($vacancy, Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors(), Response::HTTP_BAD_REQUEST));
    }

    #[Rest\Get('/single_vacancy/{id}', name: 'get_single_vacancy')]
    public function getSingleVacancy(
        Vacancy $vacancy,
        VacancyRepository $vacancyRepository,
    ): Response
    {
        $data = $vacancyRepository->find(['id' => $vacancy->getId()]);
        return $this->handleView($this->view($data, Response::HTTP_OK));
    }
}