<?php
namespace Neutron\UserBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\QueryBuilder;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\AbstractType;


class GroupFormType extends AbstractType
{
    
    protected $groupClass;
    
    protected $roleClass;
    
    public function __construct($groupClass, $roleClass)
    {
        $this->groupClass = $groupClass;
        $this->roleClass = $roleClass;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'form.name',
                'translation_domain' => 'NeutronUserBundle'
            ))
            ->add('group', 'text', array(
                'label' => 'form.name',
                'translation_domain' => 'NeutronUserBundle'
            ))
            ->add('roles', 'neutron_select_entity', array(
                'label' => 'form.roles',
                'multiple' => true,
                'property' => 'name',
                'query_builder' => function(EntityRepository $repo){
                
                    $qb = $repo->createQueryBuilder('r');
                    $qb->where('r.enabled = :enabled');
                    $qb->orderBy('r.name', 'ASC');
                    $qb->setParameter('enabled', true);
                    
                    return $qb;
                },
                'class' => $this->roleClass, 
                'configs' => array('filter' => true),
                'translation_domain' => 'FOSUserBundle'
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
            'data_class' => $this->groupClass,
            'csrf_protection' => false,
            'validation_groups' => 'Group',
        ));
    }

    public function getName()
    {
        return 'neutron_user_group_form_type';
    }
    
}
