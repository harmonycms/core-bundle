<?php

namespace Harmony\Bundle\CoreBundle\Form\Type;

use Helis\SettingsManagerBundle\Form\Type\YamlType;
use Helis\SettingsManagerBundle\Model\SettingModel;
use Helis\SettingsManagerBundle\Model\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SettingFormType
 *
 * @package Harmony\Bundle\CoreBundle\Form\Type
 */
class SettingFormType extends AbstractType
{

    /**
     * Builds the form.
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var SettingModel $model */
            if (null === $model = $event->getData()) {
                return;
            }
            if ($model->getType()->equals(Type::BOOL())) {
                $event->getForm()->add('data', CheckboxType::class, [
                    'translation_domain' => 'HelisSettingsManager',
                    'label'              => false,
                    'required'           => false,
                    'help'               => $model->getDescription()
                ]);
            } elseif ($model->getType()->equals(Type::INT())) {
                $event->getForm()->add('data', IntegerType::class, [
                    'translation_domain' => 'HelisSettingsManager',
                    'label'              => false,
                    'scale'              => 0,
                    'help'               => $model->getDescription()
                ]);
            } elseif ($model->getType()->equals(Type::FLOAT())) {
                $event->getForm()->add('data', NumberType::class, [
                    'translation_domain' => 'HelisSettingsManager',
                    'label'              => false,
                    'scale'              => 2,
                    'help'               => $model->getDescription()
                ]);
            } elseif ($model->getType()->equals(Type::YAML())) {
                $event->getForm()->add('data', YamlType::class, [
                    'translation_domain' => 'HelisSettingsManager',
                    'label'              => false,
                    'attr'               => ['rows' => 12],
                    'help'               => $model->getDescription()
                ]);
            } elseif ($model->getType()->equals(Type::CHOICE())) {
                $event->getForm()->add('data', ChoiceType::class, [
                    'translation_domain' => 'HelisSettingsManager',
                    'label'              => false,
                    'placeholder'        => 'edit.form.choice_placeholder',
                    'choices'            => $model->getChoices(),
                    'help'               => $model->getDescription()
                ]);
            } else {
                $event->getForm()->add('data', TextType::class, [
                    'translation_domain' => 'HelisSettingsManager',
                    'label'              => false,
                    'required'           => false,
                    'help'               => $model->getDescription()
                ]);
            }
        });
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SettingModel::class,
            'method'     => 'POST'
        ]);
    }
}