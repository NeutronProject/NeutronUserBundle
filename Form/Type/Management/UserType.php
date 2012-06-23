<?php
namespace Neutron\UserBundle\Form\Type\Management;

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
    
    protected $userSubscrber;
    
    public function __construct(UserSubscriber $userSubscriber)
    {
        $this->userSubscrber = $userSubscriber;
    }
    
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
            ->add('roles', 'neutron_user_role', array(
                'label' => 'form.roles',
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
            
            ->add('enabled', 'checkbox', array(
                'label' => 'form.enabled',
                'value' => 1,
                'required' => false,
                'attr' => array('class' => 'uniform'),
                'translation_domain' => 'FOSUserBundle'
            ))
            
            ->add('locked', 'checkbox', array(
                'label' => 'form.locked', 
                'value' => 1,
                'required' => false,
                'attr' => array('class' => 'uniform'),
                'translation_domain' => 'NeutronUserBundle'
            ))
            
            ->add('expired', 'checkbox', array(
                'label' => 'form.expired', 
                'value' => 1,
                'required' => false,
                'attr' => array('class' => 'uniform'),
                'translation_domain' => 'NeutronUserBundle'
            ))
            
            ->add('credentials_expire_at', 'neutron_datetimepicker', array(
                'label' => 'form.management.user.expire_at',
                'input' => 'datetime',
                'with_seconds' => true,
                'attr' => array(),
                'configs' => array(),
                'translation_domain' => 'NeutronUserBundle'
            ))
            
            ->add('lastLogin', 'neutron_plain', array(
                'label' => 'form.last_login',
                'translation_domain' => 'NeutronUserBundle'
            ))
            
            ->add('passwordRequestedAt', 'neutron_plain', array(
                'label' => 'form.password_requested_at',
                'translation_domain' => 'NeutronUserBundle'
            ))
         
        ;
        
        $builder->addEventSubscriber($this->userSubscrber);
      
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Neutron\UserBundle\Entity\User',
            'validation_groups' => function(FormInterface $form){
                $data = $form->getData(); 
                if ($data->getId()){
                    return array('Management');
                } else {
                    return array('Management', 'Management.Add');
                }
            },
        ));
    }

    public function getName()
    {
        return 'neutron_user';
    }
    
}
