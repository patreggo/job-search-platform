<?php

namespace App\Form\Resume;

use Admin\Service\StringHelperService;
use App\Entity\Resume\AwardAndAchievement;
use Common\Form\AppAbstractType;
use Exception;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AwardAndAchievementApiType extends AppAbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('awardFile', VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'label' => 'award',
                'download_uri' => true,
                'image_uri' => true,
                'asset_helper' => true,
                'attr' => [
                    "block_id" => StringHelperService::getRandomStr(20)
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => AwardAndAchievement::class,
            ]
        );
    }
}