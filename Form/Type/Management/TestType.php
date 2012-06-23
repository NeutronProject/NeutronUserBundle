<?php
namespace Neutron\UserBundle\Form\Type\Management;

use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;


class TestType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username_test', null, array(
                'label' => 'form.username', 
                'translation_domain' => 'FOSUserBundle',
                'constraints' => array(new NotBlank(), new Email()),
            ))
        ;
      
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Management'),
        ));
    }

    public function getName()
    {
        return 'neutron_test';
    }
    
}
