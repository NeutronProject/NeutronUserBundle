<?php
namespace Neutron\UserBundle\Form\Type;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;


class RoleFormType extends AbstractType
{
    
    protected $class;
    
    public function __construct($class)
    {
        $this->class = $class;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'form.name',
                'translation_domain' => 'NeutronUserBundle'
            ))
            ->add('role', 'text', array(
                'label' => 'form.role',
                'translation_domain' => 'NeutronUserBundle'
            ))
            ->add('enabled', 'checkbox', array(
                'label' => 'form.enabled', 
                'value' => true,
                'required' => false,
                'attr' => array('class' => 'uniform'),
                'translation_domain' => 'NeutronUserBundle'
            ))

        ;
        
      
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_protection' => false,
            'validation_groups' => 'Role',
        ));
    }

    public function getName()
    {
        return 'neutron_user_role_form_type';
    }
    
}
