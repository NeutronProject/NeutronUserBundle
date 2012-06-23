<?php
namespace Neutron\UserBundle\Form\Type\Profile;

use Neutron\UserBundle\Form\DataTransformer\RoleToRolesTransformer;

use Symfony\Component\Validator\Constraints\MinLength;

use Symfony\Component\Form\FormInterface;

use Neutron\UserBundle\Form\EventSubscriber\UserSubscriber;

use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;


class UserType extends AbstractType
{
        
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array(
                'label' => 'form.username', 
                'translation_domain' => 'FOSUserBundle',
            ))
            ->add('email', 'email', array(
                'label' => 'form.email', 
                'translation_domain' => 'FOSUserBundle'
            ))
 
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'required' => false,
                'type' => 'password',
                'invalid_message' => 'form.repeated_password',
                'options' => array(
                    'required' => false,
                ),
                    
                'first_options'  => array(
                    'label' => 'Password',
                    'attr' => array('class' => 'uniform'),
                ),
                'second_options' => array(
                    'label' => 'Repeat Password',
                    'attr' => array('class' => 'uniform'),
                ),
                    
                'translation_domain' => 'FOSUserBundle'
            ))
            
            ->add('currentPassword', 'password', array(
                'label' => 'form.current',
                 'property_path' => 'currentPassword',
                'translation_domain' => 'NeutronUserBundle'
            ))
         
        ;
        
      
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Neutron\UserBundle\Entity\User',
            'validation_groups' => 'Profile.Edit',
        ));
    }

    public function getName()
    {
        return 'neutron_user';
    }
    
}
