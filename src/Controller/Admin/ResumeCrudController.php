<?php

namespace App\Controller\Admin;

use App\Entity\Resume\Resume;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class ResumeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Resume::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Резюме')
            ->setEntityLabelInPlural('Резюме')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', 'Модерация резюме')
            ->setPageTitle('detail', fn (Resume $resume) => sprintf('Резюме: %s %s', $resume->getFirstName(), $resume->getLastName()))
            ->setPageTitle('edit', fn (Resume $resume) => sprintf('Редактировать: %s %s', $resume->getFirstName(), $resume->getLastName()))
            ->setPaginatorPageSize(20);
    }

    public function configureActions(Actions $actions): Actions
    {
        $approve = Action::new('approve', 'Одобрить', 'fa fa-check')
            ->linkToCrudAction('approveResume')
            ->displayIf(fn (Resume $resume) => !$resume->getIsModerated())
            ->setCssClass('btn btn-success');

        $reject = Action::new('reject', 'Отклонить', 'fa fa-times')
            ->linkToCrudAction('rejectResume')
            ->displayIf(fn (Resume $resume) => $resume->getIsModerated())
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

            TextField::new('firstName', 'Имя'),
            TextField::new('lastName', 'Фамилия'),

            AssociationField::new('user', 'Пользователь')
                ->setFormattedValue(fn ($value) => $value ? $value->getEmail() : 'Не указан')
                ->onlyOnDetail(), // Только для просмотра

            AssociationField::new('specialization', 'Специализация')
                ->setFormattedValue(fn ($value) => $value ? $value->getName() : 'Не указана'),

            BooleanField::new('isModerated', 'Модерировано')
                ->renderAsSwitch(false),

            BooleanField::new('isActive', 'Активно')
                ->renderAsSwitch(false)
                ->hideOnIndex(),

            MoneyField::new('desiredSalary', 'Желаемая зарплата')
                ->setCurrency('RUB')
                ->hideOnIndex(),

            DateField::new('birthDate', 'Дата рождения')
                ->hideOnIndex(),

            AssociationField::new('residenceCity', 'Город проживания')
                ->setFormattedValue(fn ($value) => $value ? $value->getName() : 'Не указан')
                ->hideOnIndex(),

            TextareaField::new('additionalInformation', 'Дополнительная информация')
                ->hideOnIndex()
                ->setMaxLength(500), // Увеличили лимит для редактирования

            BooleanField::new('havePersonalCar', 'Личный автомобиль')
                ->renderAsSwitch(false)
                ->hideOnIndex(),

            DateTimeField::new('createdAt', 'Создано')
                ->setFormat('dd.MM.yyyy HH:mm')
                ->onlyOnDetail(), // Только для просмотра
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(BooleanFilter::new('isModerated', 'Модерировано'))
            ->add(BooleanFilter::new('isActive', 'Активно'))
            ->add(TextFilter::new('firstName', 'Имя'))
            ->add(TextFilter::new('lastName', 'Фамилия'))
            ->add('specialization')
            ->add('user');
    }

    public function approveResume(AdminContext $context)
    {
        $resume = $context->getEntity()->getInstance();
        $resume->setIsModerated(true);
        $resume->setIsActive(true);

        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('success', sprintf('Резюме "%s %s" одобрено!', $resume->getFirstName(), $resume->getLastName()));

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }

    public function rejectResume(AdminContext $context)
    {
        $resume = $context->getEntity()->getInstance();
        $resume->setIsModerated(false);
        $resume->setIsActive(false);

        $this->container->get('doctrine')->getManager()->flush();

        $this->addFlash('warning', sprintf('Резюме "%s %s" отклонено!', $resume->getFirstName(), $resume->getLastName()));

        $referrer = $context->getReferrer();
        if ($referrer) {
            return $this->redirect($referrer);
        }

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(self::class)->setAction(Action::INDEX)->generateUrl());
    }
}
