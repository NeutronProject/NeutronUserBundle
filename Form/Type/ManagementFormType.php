<?php
namespace Neutron\UserBundle\Form\Type;

use Symfony\Component\Form\FormInterface;

use Neutron\UserBundle\Form\Type\Management\TestType;

use Neutron\UserBundle\Form\Type\Management\UserType;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;


class ManagementFormType extends AbstractType
{
    
    private $class;
    
    public function __construct($class)
    {
        $this->class = $class;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('user', 'neutron_user_type');
        //$builder->add('test', new TestType());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'neutron_user_management';
    }
    


}
