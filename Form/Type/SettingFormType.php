<?php

namespace Harmony\Bundle\CoreBundle\Form\Type;

use Helis\SettingsManagerBundle\Model\SettingModel;
use Helis\SettingsManagerBundle\Model\Type;
use Symfony\Component\Form\AbstractType;
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

            $options = [
                'translation_domain' => 'HelisSettingsManager',
                'label'              => false,
                'help'               => $model->getDescription()
            ];
            if ($model->getType()->equals(Type::BOOL()) || $model->getType()->getValue() === 'bool') {
                $options += ['required' => false];
            } elseif ($model->getType()->equals(Type::INT()) || $model->getType()->getValue() === 'int') {
                $options += ['scale' => 0];
            } elseif ($model->getType()->equals(Type::FLOAT()) || $model->getType()->getValue() === 'float') {
                $options += ['scale' => 2];
            } elseif ($model->getType()->equals(Type::YAML()) || $model->getType()->getValue() === 'yaml') {
                $options += ['attr' => ['rows' => 12]];
            } elseif ($model->getType()->equals(Type::CHOICE()) || $model->getType()->getValue() === 'choice') {
                $options += [
                    'placeholder' => 'edit.form.choice_placeholder',
                    'choices'     => array_values($model->getChoices()) === $model->getChoices() ?
                        array_combine($model->getChoices(), $model->getChoices()) : $model->getChoices()
                ];
            } else {
                $options += ['required' => false];
            }

            $event->getForm()
                ->add('data', Type::getTypeName($model->getType()->getValue()),
                    array_merge($options, $model->getTypeOptions()));
        });
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => SettingModel::class]);
    }
}