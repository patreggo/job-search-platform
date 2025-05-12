<?php

namespace App\Form\Resume;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Money\Currency;
use App\Entity\Resume\DrivingLicenseCategory;
use App\Entity\Resume\Gender;
use App\Entity\Resume\Resume;
use App\Entity\User;
use App\Entity\Vacancy\VacancyCommunicationType;
use App\Entity\Vacancy\VacancyCompanyIndustry;
use App\Entity\Vacancy\VacancyEmploymentType;
use App\Entity\Vacancy\VacancyIncomePayment;
use App\Entity\Vacancy\VacancyInteractionLanguages;
use App\Entity\Vacancy\VacancySpecializations;
use App\Entity\Vacancy\VacancyWorkSchedule;
use App\Form\AppAbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
                'salaryCurrency',
                EntityType::class,
                [
                    'label' => 'currency',
                    'class' => Currency::class,
                    'choice_label' => 'name'
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
            ->add('workPlace', EntityType::class, [
                'by_reference' => false,
                'required' => false,
                'label' => 'work_place',
                'entry_type' => WorkPlaceApiType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'allow_move' => false
            ])
            ->add('education', EntityType::class, [
                'by_reference' => false,
                'required' => true,
                'label' => 'education',
                'entry_type' => EducationApiType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'allow_move' => false,
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
                'drivingLicenseCategory',
                EntityType::class,
                [
                    'required' => true,
                    'label' => 'driving_license_category',
                    'class' => DrivingLicenseCategory::class,
                    'choice_label' => 'name',
                    'multiple' => true
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
            ->add(
                'nativeInteractionLanguage',
                EntityType::class,
                [
                    'label' => 'native_interaction_language',
                    'class' => VacancyInteractionLanguages::class,
                    'choice_label' => 'name.translatorKey',
                ]
            )
            ->add(
                'additionalLanguages',
                EntityType::class,
                [
                    'label' => 'additional_languages',
                    'class' => VacancyInteractionLanguages::class,
                    'choice_label' => 'name.translatorKey',
                    'multiple' => true
                ]
            )
            ->add('awardAndAchievement', EntityType::class, [
                'by_reference' => false,
                'required' => false,
                'label' => 'award_and_achievement',
                'entry_type' => AwardAndAchievementApiType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'allow_move' => false,
            ])
            ->add(
                'communicationType',
                EntityType::class,
                [
                    'label' => 'communication_type',
                    'class' => VacancyCommunicationType::class,
                    'choice_label' => 'name.translatorKey',
                    'multiple' => true
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
        ]);
    }
}
