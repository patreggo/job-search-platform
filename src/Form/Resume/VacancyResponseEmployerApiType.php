<?php

namespace App\Form\Resume;

use App\Entity\Resume\VacancyResponseStatus;
use App\Repository\Resume\VacancyResponseStatusRepository;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class VacancyResponseEmployerApiType extends VacancyResponseApiType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
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
                    },
                    'choice_label' => 'name',
                ]
            );
    }

}
