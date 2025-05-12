<?php

namespace App\Form\Resume;

use App\Entity\Resume\Education;
use App\Entity\Vacancy\VacancyEducation;
use Common\Form\AppAbstractType;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EducationApiType extends AppAbstractType
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
                'levelEducation',
                EntityType::class,
                [
                    'label' => 'education_level',
                    'class' => VacancyEducation::class,
                    'choice_label' => 'name.translatorKey'
                ]
            )
            ->add(
                'university',
                TextType::class,
                [
                    'label' => 'university',
                ]
            )
            ->add(
                'speciality',
                TextType::class,
                [
                    'label' => 'speciality',
                ]
            )
            ->add(
                'faculty',
                TextType::class,
                [
                    'label' => 'faculty',
                ]
            )
            ->add(
                'graduationYear',
                IntegerType::class,
                [
                    'label' => 'graduation_year',
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
                'data_class' => Education::class,
            ]
        );
    }
}