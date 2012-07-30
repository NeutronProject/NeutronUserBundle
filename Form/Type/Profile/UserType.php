<?php
namespace Neutron\UserBundle\Form\Type\Profile;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword;

use Symfony\Component\Form\FormInterface;

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
                ),
                'second_options' => array(
                    'label' => 'Repeat Password',
                ),
                    
                'translation_domain' => 'FOSUserBundle'
            ))
            
            ->add('currentPassword', 'password', array(
                'label' => 'form.current',
                'mapped' => false,
                'constraints' => array(
                    new UserPassword(array(
                        'groups' => 'Profile.Edit', 
                        'message' => 'neutron_user.current_password.message'
                    ))
                ),
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
        return 'neutron_user_profile_user';
    }
    
}
