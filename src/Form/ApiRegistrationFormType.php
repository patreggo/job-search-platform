<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\UserRoles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ApiRegistrationFormType
 * @package App\Form
 */
class ApiRegistrationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'label' => false,
                    'required' => false,
                ]
            )
            ->add(
                'password',
                PasswordType::class,
            )
            ->add(
                'firstName',TextType::class,
            )
            ->add(
                'lastName',TextType::class,
            )
            ->add(
                'phone', TextType::class,
            )
            ->add(
                'roles',
                EntityType::class,
                [
                    'label' => 'role',
                    "class" => UserRoles::class,
                ]
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => User::class,
                'csrf_protection' => false,
            ]
        );
    }
}