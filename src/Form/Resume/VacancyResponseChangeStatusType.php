<?php

namespace App\Form\Resume;

use App\Entity\Resume\VacancyResponse;
use App\Entity\Resume\VacancyResponseStatus;
use App\Repository\Resume\VacancyResponseStatusRepository;
use Common\Form\AppAbstractType;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyResponseChangeStatusType extends AppAbstractType
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
                'status',
                EntityType::class,
                [
                    'label' => 'status',
                    'required' => true,
                    'class' => VacancyResponseStatus::class,
                    'query_builder' => function (VacancyResponseStatusRepository $vacancyResponseStatusRepository) {
                        return $vacancyResponseStatusRepository->getSelectableStatusesQB();
                    }
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
            'data_class' => VacancyResponse::class
        ]);
    }
}