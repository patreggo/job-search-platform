<?php

namespace App\Form\Resume;

use App\Entity\Resume\Resume;
use App\Entity\Resume\VacancyResponse;
use App\Entity\Vacancy\Vacancy;
use Common\Form\AppAbstractType;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyResponseApiType extends AppAbstractType
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
                'resume',
                EntityType::class,
                [
                    'label' => 'resume',
                    'class' => Resume::class,
                    'choice_label' => 'name'
                ]
            )->add(
                'vacancy',
                EntityType::class,
                [
                    'label' => 'vacancy',
                    'class' => Vacancy::class,
                    'choice_label' => 'name'
                ]
            );
        $this->addSubmitType($builder);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VacancyResponse::class,
        ]);
    }
}