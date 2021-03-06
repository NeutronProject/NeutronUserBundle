<?php
namespace Neutron\UserBundle\DataGrid;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;

use Symfony\Component\Security\Core\SecurityContext;

use Doctrine\ORM\EntityManager;

use Neutron\Bundle\DataGridBundle\DataGrid\FactoryInterface;

class UserManagement
{

    protected $factory;

    protected $em;
    
    protected $securityContext;
    
    protected $translator;
    
    protected $router;

    public function __construct (FactoryInterface $factory, EntityManager $em, 
            SecurityContext $securityContext, Translator $translator, Router $router)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->translator = $translator;
        $this->router = $router;
    }

    public function build ()
    {
        
        /**
         *
         * @var DataGrid $dataGrid
         */
        $dataGrid = $this->factory->createDataGrid('user_management');
        $dataGrid->setCaption(
            $this->translator->trans('grid.user_management.title',  array(), 'NeutronUserBundle')
        )
            ->setAutoWidth(true)
            

            ->setColNames(array(
                $this->translator->trans('grid.user_management.username',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.user_management.email',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.user_management.enabled',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.user_management.locked',  array(), 'NeutronUserBundle'),
                $this->translator->trans('grid.user_management.expired',  array(), 'NeutronUserBundle'),
                
            ))
            ->setColModel(array(
            array(
                'name' => 'u.username', 
                'index' => 'u.username', 
                'width' => 200, 
                'align' => 'left', 
                'sortable' => true
            ), 
            array(
                'name' => 'u.email', 
                'index' => 'u.email', 
                'width' => 200, 
                'align' => 'left', 
                'sortable' => true
            ), 
                    
            array(
                  'name' => 'u.enabled', 
                  'index' => 'u.enabled', 
                  'width' => 40, 
                  'align' => 'left', 
                  'sortable' => true, 
                  'formatter' => 'checkbox', 
                  'search' => true, 
                  'stype' => 'select',
                  'searchoptions' => array('value' => array(1 => 'enabled', 0 => 'disabled'))
            ),
            array(
                  'name' => 'u.locked', 
                  'index' => 'u.locked', 
                  'width' => 40, 
                  'align' => 'left', 
                  'sortable' => true, 
                  'formatter' => 'checkbox', 
                  'search' => true, 
                  'stype' => 'select',
                  'searchoptions' => array('value' => array(1 => 'locked', 0 => 'not_locked'))
            ),
            array(
                  'name' => 'u.expired', 
                  'index' => 'u.expired', 
                  'width' => 40, 
                  'align' => 'left', 
                  'sortable' => true, 
                  'formatter' => 'checkbox', 
                  'search' => true, 
                  'stype' => 'select',
                  'searchoptions' => array('value' => array(1 => 'expired', 0 => 'not_expired'))
            ),  
                    
            ))
            ->setQueryBuilder($this->getQb())
            ->setSortName('u.username')
            ->setSortOrder('asc')
            ->enablePager(true)
            ->enableViewRecords(true)
            ->enableSearchButton(true)
            ->enableAddButton(true)
            ->setAddBtnUri($this->router->generate('neutron_user_management_add'))
            ->enableEditButton(true)
            ->setEditBtnUri($this->router->generate('neutron_user_management_edit', array('rowId' => '{id}'), true))
            ->enableDeleteButton(true)
            ->setDeleteBtnUri($this->router->generate('neutron_user_management_delete', array('rowId' => '{id}'), true))
            ->enableMassActions(true)
            ->addMassAction('enableUsers', $this->translator->trans('grid.user_management.enable_users', array(), 'NeutronUserBundle'))
            ->addMassAction('disableUsers', $this->translator->trans('grid.user_management.disable_users', array(), 'NeutronUserBundle'))
            ->addMassAction('lockUsers', $this->translator->trans('grid.user_management.lock_users', array(), 'NeutronUserBundle'))
            ->addMassAction('unlockUsers', $this->translator->trans('grid.user_management.unlock_users', array(), 'NeutronUserBundle'))
            ->setFetchJoinCollection(true)
        ;


        
        return $dataGrid;
    }

    private function getQb ()
    {  
        $conn = $this->em->getConnection();
        $qb = $this->em->createQueryBuilder();
        
        $qb
            ->select('u.id, u.username, u.email, u.enabled, u.locked, u.expired')
            ->from('Neutron\UserBundle\Entity\User', 'u')
    
            ->where($qb->expr()->neq('u.username', $conn->quote($this->getUsername())))
            
        ;
        
        return $qb;
    }
    
    private function getUsername()
    {
        return $this->securityContext->getToken()->getUser()->getUsername();
    }

}