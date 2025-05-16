<?php

namespace App\Form\Resume;

use App\Entity\City;
use App\Entity\Country;

use App\Entity\Resume\AwardAndAchievement;
use App\Entity\Resume\Gender;
use App\Entity\Resume\Resume;
use App\Entity\Vacancy\VacancyCompanyIndustry;
use App\Entity\Vacancy\VacancyEmploymentType;
use App\Entity\Vacancy\VacancyIncomePayment;
use App\Entity\Vacancy\VacancySpecializations;
use App\Entity\Vacancy\VacancyWorkSchedule;
use App\Form\AppAbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResumeApiType extends AppAbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'specialization',
                EntityType::class,
                [
                    'label' => 'specializations',
                    'class' => VacancySpecializations::class,
                    'choice_label' => 'name.translatorKey',
                ]
            )
            ->add(
                'desiredSalary',
                NumberType::class,
                [
                    'label' => 'desired_salary',
                    'required' => false,
                    'scale' => 2,
                ]
            )
                        ->add(
                'gender',
                EntityType::class,
                [
                    'label' => 'gender',
                    'class' => Gender::class,
                    'choice_label' => 'name'
                ]
            )
            ->add(
                'jobIndustry',
                EntityType::class,
                [
                    'label' => 'company_industry',
                    'class' => VacancyCompanyIndustry::class,
                    'choice_label' => 'name.translatorKey',
                ]
            )
            ->add(
                'incomePayment',
                EntityType::class,
                [
                    'label' => 'income_payment',
                    'class' => VacancyIncomePayment::class,
                    'choice_label' => 'name.translatorKey',
                ]
            )
            ->add(
                'firstName',
                TextType::class,
                [
                    'label' => 'first_name',
                ]
            )
            ->add(
                'lastName',
                TextType::class,
                [
                    'label' => 'last_name',
                ]
            )
            ->add(
                'birthDate',
                BirthdayType::class,
                [
                    'label' => 'birth_date',
                    'input' => 'datetime',
                    'required' => false,
                    'years' => range((int)date('Y'), (int)date('Y') - 60),
                ]
            )
            ->add(
                'residenceCity',
                EntityType::class,
                [
                    'label' => 'residence_city',
                    'class' => City::class,
                    'choice_label' => 'name',
                ]
            )
            ->add(
                'citizenship',
                EntityType::class,
                [
                    'label' => 'citizenship',
                    'class' => Country::class,
                    'choice_label' => 'name',
                    'multiple' => true
                ]
            )
            ->add('workPlace', CollectionType::class, [
                'entry_type' => WorkPlaceApiType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add('education', CollectionType::class, [
                'entry_type' => EducationApiType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
            ])
            ->add(
                'additionalInformation',
                TextareaType::class,
                [
                    'label' => 'additional_information',
                ]
            )
            ->add(
                'relocationCity',
                EntityType::class,
                [
                    'label' => 'relocation_city',
                    'class' => City::class,
                    'choice_label' => 'name',
                    'multiple' => true
                ]
            )
            ->add(
                'employmentType',
                EntityType::class,
                [
                    'label' => 'employment_type',
                    'class' => VacancyEmploymentType::class,
                    'choice_label' => 'name.translatorKey',
                    'multiple' => true
                ]
            )
            ->add(
                'workSchedule',
                EntityType::class,
                [
                    'label' => 'work_schedule',
                    'class' => VacancyWorkSchedule::class,
                    'choice_label' => 'name.translatorKey',
                ]
            )

            ->add(
                'havePersonalCar',
                CheckboxType::class,
                [
                    'label' => 'have_personal_car',
                    'required' => false,
                ]
            )
            ->add(
                'workPermitCountry',
                EntityType::class,
                [
                    'label' => 'work_permit',
                    'class' => Country::class,
                    'choice_label' => 'name',
                    'multiple' => true
                ]
            )
            ->add('awardAndAchievement', EntityType::class, [
                'label' => 'income_payment',
                'class' => AwardAndAchievement::class,
                'choice_label' => 'name.translatorKey',
                'multiple' => true
            ])
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'label' => 'is_active',
                    'required' => false,
                ]
            );

        $this->addSluggerType($builder, 'lastName');
        $this->addSubmitType($builder);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
            'csrf_protection' => false,
        ]);
    }
}
