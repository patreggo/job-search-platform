<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/{reactRouting}', name: 'vue_app', requirements: ['reactRouting' => '^(?!api).+'], condition: "context.getMethod() === 'GET' && request.isXmlHttpRequest() === false")]
    public function index():Response{
        return $this->render('base.html.twig');
    }
}