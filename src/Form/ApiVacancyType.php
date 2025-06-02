<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Vacancy\Vacancy;
use App\Entity\Vacancy\VacancyCompanyIndustry;
use App\Entity\Vacancy\VacancyEducation;
use App\Entity\Vacancy\VacancyEmploymentType;
use App\Entity\Vacancy\VacancyIncomePayment;
use App\Entity\Vacancy\VacancySpecializations;
use App\Entity\Vacancy\VacancyWorkExperience;
use App\Entity\Vacancy\VacancyWorkSchedule;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApiVacancyType extends AppAbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name', TextType::class,
                [
                    'label' => 'name',
                    'required' => false,
                ]
            )
            ->add('incomeMin', NumberType::class, [
                'label' => 'income_min',
                'required' => false,
            ])
            ->add('incomeMax', NumberType::class, [
                'label' => 'income_max',
                'required' => false,
            ])
            ->add(
                'workAddress', TextType::class,
                [
                    'label' => 'work_address',
                    'required' => false,
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'description',
                ]
            )
            ->add(
                'requirements',
                TextType::class,
                [
                    'label' => 'requirements',
                ]
            )
            ->add(
                'responsibilities',
                TextType::class,
                [
                    'label' => 'responsibilities',
                ]
            )
            ->add('attachmentDocumentFile', FileType::class, [
                'required' => false,
                'label' => 'attachments',
            ])
            ->add(
                'incomePayment',
                EntityType::class,
                [
                    'label' => 'income_payment',
                    'class' => VacancyIncomePayment::class,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'company',
                EntityType::class,
                [
                    'label' => 'company',
                    'class' => Company::class,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'country',
                EntityType::class,
                [
                    'label' => 'country',
                    'class' => Country::class,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'city',
                EntityType::class,
                [
                    'label' => 'city',
                    'class' => City::class,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'workSchedule',
                EntityType::class,
                [
                    'label' => 'work_schedule',
                    'class' => VacancyWorkSchedule::class,
                    'multiple' => true,
                    'choice_label' => 'name.translatorKey'
                ]
            )
            ->add(
                'companyIndustry',
                EntityType::class,
                [
                    'label' => 'company_industry',
                    'class' => VacancyCompanyIndustry::class,
                    'multiple' => true,
                    'choice_label' => 'name.translatorKey'
                ]
            )
            ->add(
                'specializations',
                EntityType::class,
                [
                    'label' => 'specializations',
                    'class' => VacancySpecializations::class,
                    'multiple' => true,
                    'choice_label' => 'name.translatorKey'
                ]
            )
            ->add(
                'employmentType',
                EntityType::class,
                [
                    'label' => 'employment_type',
                    'class' => VacancyEmploymentType::class,
                    'multiple' => true,
                    'choice_label' => 'name.translatorKey'
                ]
            )
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
                'searchCities',
                EntityType::class,
                [
                    'label' => 'city',
                    'class' => City::class,
                    'multiple' => true,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'archived',
                CheckboxType::class,
                [
                    'label' => 'archived',
                    'required' => false,
                ]
            )
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'label' => 'is_active',
                    'required' => false,
                ]
            );
        $this->addIsEnabledType($builder);
        $this->addSluggerType($builder, 'name');
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vacancy::class,
            'csrf_protection' => false,
        ]);
    }
}
