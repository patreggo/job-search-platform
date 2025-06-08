<?php

namespace App\Controller\Admin;

use App\Entity\Vacancy\Vacancy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class VacancyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Vacancy::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Вакансия')
            ->setEntityLabelInPlural('Вакансии')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Модерация вакансий')
            ->setPageTitle('detail', fn (Vacancy $vacancy) => sprintf('Вакансия: %s', $vacancy->getName()))
            ->setPageTitle('edit', fn (Vacancy $vacancy) => sprintf('Редактировать: %s', $vacancy->getName()))
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        $approve = Action::new('approve', 'Одобрить', 'fa fa-check')
            ->linkToCrudAction('approveVacancy')
            ->displayIf(fn (Vacancy $vacancy) => !$vacancy->getIsModerated())
            ->setCssClass('btn btn-success');

        $reject = Action::new('reject', 'Отклонить', 'fa fa-times')
            ->linkToCrudAction('rejectVacancy')
            ->displayIf(fn (Vacancy $vacancy) => $vacancy->getIsModerated())
            ->setCssClass('btn btn-danger');

        return $actions
            // Добавляем кастомные действия модерации
            ->add(Crud::PAGE_INDEX, $approve)
            ->add(Crud::PAGE_INDEX, $reject)
            ->add(Crud::PAGE_DETAIL, $approve)
            ->add(Crud::PAGE_DETAIL, $reject)

            // Оставляем стандартные действия просмотра и редактирования
            ->add(Crud::PAGE_INDEX, Action::DETAIL)

            // Убираем только создание и удаление
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->onlyOnIndex(),

            TextField::new('name', 'Название')
                ->setMaxLength(50),

            AssociationField::new('company', 'Компания')
                ->setFormattedValue(fn ($value) => $value ? $value->getName() : 'Не указана')
                ->onlyOnDetail(), // Только для просмотра, не редактируем связь

            BooleanField::new('isModerated', 'Модерирована')
                ->renderAsSwitch(false),

            BooleanField::new('isActive', 'Активна')
                ->renderAsSwitch(false)
                ->hideOnIndex(),

            BooleanField::new('isEnabled', 'Включена')
                ->renderAsSwitch(false)
                ->hideOnIndex(),

            MoneyField::new('incomeMin', 'Зарплата от')
                ->setCurrency('RUB')
                ->hideOnIndex(),

            MoneyField::new('incomeMax', 'Зарплата до')
                ->setCurrency('RUB')
                ->hideOnIndex(),

            TextField::new('workAddress', 'Адрес работы')
                ->hideOnIndex(),

            TextareaField::new('description', 'Описание')
                ->hideOnIndex()
                ->setMaxLength(1000), // Увеличили лимит для редактирования

            TextareaField::new('requirements', 'Требования')
                ->hideOnIndex()
                ->setMaxLength(1000),

            TextareaField::new('responsibilities', 'Обязанности')
                ->hideOnIndex()
                ->setMaxLength(1000),


            DateTimeField::new('createdAt', 'Создана')
                ->setFormat('dd.MM.yyyy HH:mm')
                ->onlyOnDetail(), // Только для просмотра

            DateTimeField::new('publicationDate', 'Дата публикации')
                ->setFormat('dd.MM.yyyy HH:mm')
                ->hideOnIndex(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isModerated', 'Модерирована'))
            ->add(BooleanFilter::new('isActive', 'Активна'))
            ->add(TextFilter::new('name', 'Название'))
            ->add('company');
    }

    public function approveVacancy(AdminContext $context)
    {
        $vacancy = $context->getEntity()->getInstance();
        $vacancy->setIsModerated(true);
        $vacancy->setIsActive(true);

        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('success', sprintf('Вакансия "%s" одобрена!', $vacancy->getName()));

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function rejectVacancy(AdminContext $context)
    {
        $vacancy = $context->getEntity()->getInstance();
        $vacancy->setIsModerated(false);
        $vacancy->setIsActive(false);

        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('warning', sprintf('Вакансия "%s" отклонена!', $vacancy->getName()));

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }
}
