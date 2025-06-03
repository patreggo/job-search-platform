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
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Админ панель - Модерация')
            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Главная', 'fa fa-home');

        // Статистика
        yield MenuItem::section('Статистика');
        yield MenuItem::linkToUrl('Дашборд', 'fa fa-chart-bar', $this->generateUrl('admin_stats'));

        // Модерация
        yield MenuItem::section('Модерация');

        yield MenuItem::linkToCrud('Вакансии на модерации', 'fa fa-briefcase', Vacancy::class)
            ->setQueryParameter('filters[isModerated]', '0');

        yield MenuItem::linkToCrud('Резюме на модерации', 'fa fa-file-text', Resume::class)
            ->setQueryParameter('filters[isModerated]', '0');

        yield MenuItem::linkToCrud('Компании на модерации', 'fa fa-building', Company::class)
            ->setQueryParameter('filters[isConfirmed]', '0');

        // Все записи
        yield MenuItem::section('Все записи');
        yield MenuItem::linkToCrud('Все вакансии', 'fa fa-briefcase', Vacancy::class);
        yield MenuItem::linkToCrud('Все резюме', 'fa fa-file-text', Resume::class);
        yield MenuItem::linkToCrud('Все компании', 'fa fa-building', Company::class);
    }

    #[Route('/admin/stats', name: 'admin_stats')]
    public function stats(
        EntityManagerInterface $entityManager,
    ): Response
    {
        $pendingVacancies = $entityManager->getRepository(Vacancy::class)
            ->count(['isModerated' => false]);

        $approvedVacancies = $entityManager->getRepository(Vacancy::class)
            ->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.isModerated = true AND v.moderationStatus = :status')
            ->setParameter('status', 'approved')
            ->getQuery()
            ->getSingleScalarResult();

        $rejectedVacancies = $entityManager->getRepository(Vacancy::class)
            ->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->where('v.isModerated = true AND v.moderationStatus = :status')
            ->setParameter('status', 'rejected')
            ->getQuery()
            ->getSingleScalarResult();

        $pendingResumes = $entityManager->getRepository(Resume::class)
            ->count(['isModerated' => false]);

        $approvedResumes = $entityManager->getRepository(Resume::class)
            ->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.isModerated = true AND r.moderationStatus = :status')
            ->setParameter('status', 'approved')
            ->getQuery()
            ->getSingleScalarResult();

        $rejectedResumes = $entityManager->getRepository(Resume::class)
            ->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->where('r.isModerated = true AND r.moderationStatus = :status')
            ->setParameter('status', 'rejected')
            ->getQuery()
            ->getSingleScalarResult();

        $pendingCompanies = $entityManager->getRepository(Company::class)
            ->count(['isConfirmed' => false]);

        $approvedCompanies = $entityManager->getRepository(Company::class)
            ->count(['isConfirmed' => true]);

        return $this->render('admin/stats.html.twig', [
            'pending_vacancies' => $pendingVacancies,
            'approved_vacancies' => $approvedVacancies,
            'rejected_vacancies' => $rejectedVacancies,
            'pending_resumes' => $pendingResumes,
            'approved_resumes' => $approvedResumes,
            'rejected_resumes' => $rejectedResumes,
            'pending_companies' => $pendingCompanies,
            'approved_companies' => $approvedCompanies,
        ]);
    }
}
