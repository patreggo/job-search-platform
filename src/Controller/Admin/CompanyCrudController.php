<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class CompanyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Компания')
            ->setEntityLabelInPlural('Компании')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Модерация компаний')
            ->setPageTitle('detail', fn (Company $company) => sprintf('Компания: %s', $company->getName()))
            ->setPageTitle('edit', fn (Company $company) => sprintf('Редактировать: %s', $company->getName()))
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        $approve = Action::new('approve', 'Одобрить', 'fa fa-check')
            ->linkToCrudAction('approveCompany')
            ->displayIf(fn (Company $company) => !$company->isIsConfirmed())
            ->setCssClass('btn btn-success');

        $reject = Action::new('reject', 'Отклонить', 'fa fa-times')
            ->linkToCrudAction('rejectCompany')
            ->displayIf(fn (Company $company) => $company->isIsConfirmed())
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

            TextField::new('name', 'Название компании')
                ->setMaxLength(50),

            AssociationField::new('user', 'Владелец')
                ->setFormattedValue(fn ($value) => $value ? $value->getEmail() : 'Не указан')
                ->onlyOnDetail(), // Показываем только для просмотра, не редактируем

            BooleanField::new('isConfirmed', 'Подтверждена')
                ->renderAsSwitch(false),

            BooleanField::new('isEnabled', 'Активна')
                ->renderAsSwitch(false)
                ->hideOnIndex(),

            EmailField::new('email', 'Email'),

            TelephoneField::new('contactPhone', 'Телефон')
                ->hideOnIndex(),

            TextareaField::new('description', 'Описание')
                ->hideOnIndex()
                ->setMaxLength(500), // Увеличили лимит для редактирования


            DateTimeField::new('createdAt', 'Создана')
                ->setFormat('dd.MM.yyyy HH:mm')
                ->onlyOnDetail(), // Только для просмотра

            // Показать количество вакансий
            TextField::new('vacanciesCount', 'Вакансий')
                ->setFormattedValue(fn (Company $company) => $company->getVacancies()->count())
                ->onlyOnIndex(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isConfirmed', 'Подтверждена'))
            ->add(BooleanFilter::new('isEnabled', 'Активна'))
            ->add(TextFilter::new('name', 'Название'))
            ->add(TextFilter::new('email', 'Email'))
            ->add('user');
    }

    public function approveCompany(AdminContext $context)
    {
        $company = $context->getEntity()->getInstance();
        $company->setIsConfirmed(true);
        $company->setIsEnabled(true);

        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('success', sprintf('Компания "%s" одобрена!', $company->getName()));

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function rejectCompany(AdminContext $context)
    {
        $company = $context->getEntity()->getInstance();
        $company->setIsConfirmed(false);
        $company->setIsEnabled(false);

        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('warning', sprintf('Компания "%s" отклонена!', $company->getName()));

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }
}
