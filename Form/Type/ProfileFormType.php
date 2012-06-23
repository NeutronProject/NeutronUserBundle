<?php
namespace Neutron\UserBundle\Form\Type;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;


class ProfileFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder->add('user', 'neutron_user_profile_user');
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
        return 'neutron_user_profile';
    }
    


}
