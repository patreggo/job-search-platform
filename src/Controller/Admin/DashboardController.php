<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Resume\Resume;
use App\Entity\Vacancy\Vacancy;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        // Перенаправляем на страницу с немодерированными вакансиями
        return $this->redirect($adminUrlGenerator->setController(VacancyCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Админ-панель модерации')
            ->setLocales(['ru' => '🇷🇺 Русский']);

    }

    public function configureMenuItems(): iterable
    {
        // Главная страница
        yield MenuItem::linkToDashboard('Главная', 'fa fa-home');

        // Модерация
        yield MenuItem::section('Модерация');

        yield MenuItem::linkToCrud('Вакансии', 'fa fa-briefcase', Vacancy::class)
            ->setDefaultSort(['createdAt' => 'DESC']);

        yield MenuItem::linkToCrud('Резюме', 'fa fa-file-text', Resume::class)
            ->setDefaultSort(['createdAt' => 'DESC']);

        yield MenuItem::linkToCrud('Компании', 'fa fa-building', Company::class)
            ->setDefaultSort(['createdAt' => 'DESC']);

        // Статистика
        yield MenuItem::section('Статистика');

        yield MenuItem::linkToUrl('На сайт', 'fa fa-external-link', '/');
    }
}
