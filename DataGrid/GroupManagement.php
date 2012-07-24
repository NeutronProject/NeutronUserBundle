<?php
namespace Neutron\UserBundle\DataGrid;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Symfony\Component\Security\Core\SecurityContext;

use Doctrine\ORM\EntityManager;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class GroupManagement
{

    protected $factory;

    protected $em;
    
    protected $translator;
    
    protected $router;

    public function __construct (FactoryInterface $factory, EntityManager $em, 
            Translator $translator, Router $router)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function build ()
    {
        
        /**
         *
         * @var DataGrid $dataGrid
         */
        $dataGrid = $this->factory->createDataGrid('group_management');
        $dataGrid->setCaption(
            $this->translator->trans('grid.group_management.title',  array(), 'NeutronUserBundle')
        )
            ->setAutoWidth(true)
            ->setColNames(array(
                $this->translator->trans('grid.group_management.name',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.group_management.enabled',  array(), 'NeutronUserBundle'),
  
                
            ))
            ->setColModel(array(
                array(
                    'name' => 'g.name', 
                    'index' => 'g.name', 
                    'width' => 200, 
                    'align' => 'left', 
                    'sortable' => true,
                    'search' => true,
                ), 
                        
                array(
                    'name' => 'g.enabled', 
                    'index' => 'g.enabled', 
                    'width' => 40, 
                    'align' => 'left', 
                    'sortable' => true, 
                    'formatter' => 'checkbox', 
                    'search' => true, 
                    'stype' => 'select',
                    'searchoptions' => array('value' => array(1 => 'enabled', 0 => 'disabled'))
                ),
     
            ))
            ->setQueryBuilder($this->getQb())
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableAddButton(true)
            ->setAddBtnUri($this->router->generate('neutron_user_group_manipulate'))
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_user_group_manipulate', array('id' => '{{ rowId }}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_user_group_delete', array('id' => '{{ rowId }}'), true))
            
        ;


        
        return $dataGrid;
    }

    private function getQb ()
    {
        return $this->em->createQueryBuilder()
            ->select(array('g.id', 'g.name', 'g.enabled'))
            ->from('NeutronUserBundle:Group', 'g')
            
        ;
    }

}