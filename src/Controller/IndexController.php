<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{
    #[Route(path: '/', name: 'vue_app_root')]
    public function indexHome(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route(path: '/{vueRouting}', name: 'vue_app', requirements: ['vueRouting' => '^(?!api).+'])]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }
}