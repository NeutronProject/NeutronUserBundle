<?php
namespace Neutron\UserBundle\DataGrid;

use Doctrine\ORM\Query;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Symfony\Component\Security\Core\SecurityContext;

use Doctrine\ORM\EntityManager;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class RoleManagement
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
        $dataGrid = $this->factory->createDataGrid('role_management');
        $dataGrid->setCaption(
            $this->translator->trans('grid.role_management.title',  array(), 'NeutronUserBundle')
        )
            ->setAutoWidth(true)
            ->setColNames(array(
                $this->translator->trans('grid.role_management.name',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.role_management.role',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.role_management.enabled',  array(), 'NeutronUserBundle'),
  
                
            ))
            ->setColModel(array(
                array(
                    'name' => 'r.name', 
                    'index' => 'r.name', 
                    'width' => 200, 
                    'align' => 'left', 
                    'sortable' => true,
                    'search' => true,
                ), 
                    
                array(
                    'name' => 'r.role', 
                    'index' => 'r.role', 
                    'width' => 200, 
                    'align' => 'left', 
                    'sortable' => true,
                    'search' => true,
                ), 
                        
                array(
                    'name' => 'r.enabled', 
                    'index' => 'r.enabled', 
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
            ->setAddBtnUri($this->router->generate('neutron_user_role_manipulate'))
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_user_role_manipulate', array('id' => '{id}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_user_role_delete', array('id' => '{id}'), true))
            ->setQueryHints(array(
                Query::HINT_CUSTOM_OUTPUT_WALKER => 'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker',
            ))
        ;


        
        return $dataGrid;
    }

    private function getQb ()
    {
        return $this->em->createQueryBuilder()
            ->select(array('r.id', 'r.name', 'r.role', 'r.enabled'))
            ->from('NeutronUserBundle:Role', 'r')
            
        ;
    }

}