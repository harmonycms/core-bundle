<?php

namespace Harmony\Bundle\CoreBundle\Form\Type;

use Harmony\Bundle\CoreBundle\Form\DataTransformer\UserRoleTransform;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserRoleType
 *
 * @package Harmony\Bundle\CoreBundle\Form\Type
 */
class UserRoleType extends AbstractType
{

    /**
     * @var array
     */
    private $roleHierarchy;

    /**
     * UserRoleType constructor.
     *
     * @param array $roleHierarchy
     */
    public function __construct(array $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

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
        $builder->addModelTransformer(new UserRoleTransform());
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $roles = ['ROLE_USER' => 'ROLE_USER'];
        foreach ($this->roleHierarchy as $key => $value) {
            $roles[$key] = $key;
        }
        ksort($roles);

        $resolver->setDefaults(['expanded' => false, 'multiple' => true, 'choices' => $roles]);
    }

    /**
     * Returns the name of the parent type.
     *
     * @return string|null The name of the parent type if any, null otherwise
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

}