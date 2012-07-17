<?php
namespace Neutron\UserBundle\Form\Type;


use Symfony\Component\Validator\Constraints\Choice;

use Neutron\UserBundle\Model\BackendRoles;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Neutron\UserBundle\Form\DataTransformer\RoleToRolesTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class RoleFormType extends AbstractType
{
    protected $roleToRolesTransformer;

    public function __construct(RoleToRolesTransformer $roleToRolesTransformer)
    {
        $this->roleToRolesTransformer = $roleToRolesTransformer;
    }

    /**
     * @see Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->roleToRolesTransformer);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
        $resolver->setDefaults(array(
            'multiple' => false,
            'expanded' => false,
            'invalid_message' => 'neutron_user.form.invalid_role',
            'empty_value' => 'form.select',
            'constraints' => new Choice(array('choices' => BackendRoles::getRoles())),
            'empty_data' => 'none',
            'choices' => array(
                'form.backend_roles' => BackendRoles::getRoles(),
            ),
        ));
    }

    /**
     * @see Symfony\Component\Form\AbstractType::getParent()
     */
    public function getParent()
    {
        return 'neutron_chosen';
    }

    /**
     * @see Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'neutron_user_role';
    }
}
