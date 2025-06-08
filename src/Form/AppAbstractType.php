<?php

namespace App\Form;

use App\Traits\PositionTrait;
use App\Helpers\ClassHelper;
use App\Service\StringHelperService;
use App\Traits\SlugTrait;
use Exception;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AppAbstractType extends AbstractType
{
    /**
     * AbstractAdminType constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(protected TranslatorInterface $translator)
    {
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return bool
     */
    public function isNewForm(FormBuilderInterface $builder): bool
    {
        try {
            $id = $builder->getData()->getId();

            if (null === $id) {
                return true;
            }

            return false;
        } catch (\Error $e) {
            return true;
        }
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @return AppAbstractType
     *
     * @throws Exception
     */
    public function sortByPosition(FormBuilderInterface $builder, array $options): self
    {
        $position = $this->translator->trans('position', [], 'admin');
        $builder->add(
            'position',
            IntegerType::class,
            [
                'label' => $position,
                'attr' => [
                    'autocomplete' => $position,
                    'placeholder' => $position,
                ],
            ]
        );

        $data = clone $options['data'];

        ClassHelper::checkUseTrait($data::class, PositionTrait::class);

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addIsEnabledType(FormBuilderInterface $builder): self
    {
        $enabledLabel = $this->translator->trans('is_enabled');

        $builder->add(
            'isEnabled',
            CheckboxType::class,
            [
                'label' => $enabledLabel,
                'required' => false,
                'attr' => [
                    'autocomplete' => $enabledLabel,
                    'placeholder' => $enabledLabel,
                ],
            ]
        );

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addIsBlockedType(FormBuilderInterface $builder): self
    {
        $blockedLabel = $this->translator->trans('is_blocked', [], 'admin');

        $builder->add(
            'isBlocked',
            CheckboxType::class,
            [
                'label' => $blockedLabel,
                'required' => false,
                'attr' => [
                    'autocomplete' => $blockedLabel,
                    'placeholder' => $blockedLabel,
                ],
            ]
        );

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addIsModeratedType(FormBuilderInterface $builder): self
    {

        $builder->add(
            'isModerated',
            CheckboxType::class,
            [
                'label' => "Промодерировано",
                'required' => false,
                'attr' => [
                    'autocomplete' => "Промодерировано",
                    'placeholder' => "Промодерировано",
                ],
            ]
        );

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return AppAbstractType
     */
    public function addSubmitType(FormBuilderInterface $builder): self
    {
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => $this->translator->trans('submit_form_button', [], 'admin'),
                'priority' => -50
            ]
        );

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     *
     * @return AppAbstractType
     */
    public function addViewType(FormBuilderInterface $builder): self
    {
        $view = $this->translator->trans('view', [], 'admin');

        $builder->add(
            'view',
            IntegerType::class,
            [
                'label' => $view,
                'attr' => [
                    'autocomplete' => $view,
                    'placeholder' => $view,
                ],
            ]
        );

        return $this;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param string $propertyName
     * @param bool $unique
     * @return $this
     *
     * @throws Exception
     */
    public function addSluggerType(
        FormBuilderInterface $builder,
        string $propertyName = 'title',
        bool $unique = true
    ): self {
        ClassHelper::checkUseTrait($builder->getDataClass(), SlugTrait::class);

        $newForm = $this->isNewForm($builder);
        $builder->add(
            'slug',
            TextType::class,
            [
                'label' => $this->translator->trans('slug', [], 'admin'),
                'disabled' => true,
                'required' => false,
            ]
        );

        if ($newForm) {
            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use (
                $propertyName,
                $unique
            ) {
                $form = $event->getForm();
                $object = $event->getData();
                $propertyGetter = 'get' . ucfirst($propertyName);

                if (!method_exists($object, $propertyGetter)) {
                    $form->addError(new FormError(sprintf("The %s method was not found", $propertyGetter)));
                    return;
                }

                $objectField = $object->$propertyGetter();

                if ($objectField !== null) {
                    $slug = StringHelperService::generateSlugForEntity($objectField, $unique);

                    if (!$slug) {
                        $form->get('slug')->addError(new FormError("Couldn't generate slug."));
                    } else {
                        $object->setSlug($slug);
                    }
                }
            });
        }

        return $this;
    }


    /**
     * @param int $calcYear
     * @return array
     */
    private function getDateRangeYears(int $calcYear): array
    {
        return range((new \DateTime())->modify('+' . $calcYear . ' year')->format('Y'), date('Y'));
    }
}