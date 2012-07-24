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
                'class' => 'Neutron\UserBundle\Entity\Role', 
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
            'data_class' => $this->class,
            'csrf_protection' => false,
            'validation_groups' => 'Group',
        ));
    }

    public function getName()
    {
        return 'neutron_user_group_form_type';
    }
    
}
