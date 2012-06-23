<?php
namespace Neutron\UserBundle\EventListener\DataGrid\UserManagement;

use Neutron\Bundle\DataGridBundle\Event\MassActionEvent;

use Doctrine\ORM\EntityManager;

class MassActionListener
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;

    }

    public function onMassAction(MassActionEvent $event)
    {
        $ids = $event->getIds();
        
        if (!$event->getDataGridName() == 'user_management' || empty($ids)){
            return null;
        }
        
        $result = 0;
        
        if (method_exists($this, $event->getMassActionName())){
            $result = call_user_func_array(array($this, $event->getMassActionName()), array('ids' => $ids));
        }
   
        $event->setExtraData(array('num_records_affected' => $result));
    }
    
    
    public function enableUsers(array $ids)
    {
        
        $query =
            $this->em->createQuery("UPDATE NeutronUserBundle:User u SET u.enabled = true WHERE u.id IN (:ids)");
        
        return $query->execute(array('ids' => $ids));
    }
    
    private function disableUsers(array $ids)
    {
        
        $query =
            $this->em->createQuery("UPDATE NeutronUserBundle:User u SET u.enabled = false WHERE u.id IN (:ids)");
        
        return $query->execute(array('ids' => $ids));
    }
    
    private function lockUsers(array $ids)
    {
        
        $query =
            $this->em->createQuery("UPDATE NeutronUserBundle:User u SET u.locked = true WHERE u.id IN (:ids)");
        
        return $query->execute(array('ids' => $ids));
    }
    
    private function unlockUsers(array $ids)
    {
        
        $query =
            $this->em->createQuery("UPDATE NeutronUserBundle:User u SET u.locked = false WHERE u.id IN (:ids)");
        
        return $query->execute(array('ids' => $ids));
    }


}
