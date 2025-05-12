<?php

namespace App\Form\Resume;

use App\Entity\Resume\WorkPlace;
use App\Entity\Vacancy\VacancyWorkExperience;
use Common\Form\AppAbstractType;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkPlaceApiType extends AppAbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'workExperience',
                EntityType::class,
                [
                    'label' => 'work_experience',
                    'class' => VacancyWorkExperience::class,
                    'choice_label' => 'name.translatorKey'
                ]
            )
            ->add(
                'startDate',
                DateTimeType::class,
                [
                    'label' => 'start_date',
                ]
            )
            ->add(
                'endDate',
                DateTimeType::class,
                [
                    'label' => 'end_date',
                ]
            )
            ->add(
                'organizationName',
                TextType::class,
                [
                    'label' => 'organization_name',
                ]
            )
            ->add(
                'professionName',
                TextType::class,
                [
                    'label' => 'profession_name',
                ]
            )
            ->add(
                'workComposition',
                TextType::class,
                [
                    'label' => 'work_composition',
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => WorkPlace::class,
            ]
        );
    }
}