<?php
namespace Neutron\UserBundle\EventSubscriber\Doctrine;

use Doctrine\ORM\Event\PostFlushEventArgs;

use Doctrine\ORM\Event\OnFlushEventArgs;

use Doctrine\DBAL\Connection;

use Neutron\UserBundle\Model\RoleInterface;

use Doctrine\ORM\Events;

use Doctrine\Common\EventSubscriber;

class RoleSubscriber implements EventSubscriber
{
    
    protected $rolesScheduledForUpdates;
    
    protected $rolesScheduledForDeletions;

    
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $this->rolesScheduledForUpdates = array();
        $this->rolesScheduledForDeletions = array();
        
        foreach ($em->getUnitOfWork()->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof RoleInterface) {
                $changeset = $em->getUnitOfWork()->getEntityChangeSet($entity);
                if (isset($changeset['role'])) {
                    list($originalValue, $currentValue) = $changeset['role'];
                    if ($originalValue !== $currentValue){
                        $this->rolesScheduledForUpdates[] = 
                            array($currentValue, $originalValue);
                    }
                }
                
            }
        }
        
        foreach ($em->getUnitOfWork()->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof RoleInterface) {
                $this->rolesScheduledForDeletions[] = $entity->getRole();
            }
        }

    
    }
    
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        $conn = $eventArgs->getEntityManager()->getConnection();
        
        foreach ($this->rolesScheduledForUpdates as $changeSet){
            $this->updateRole($conn, $changeSet);
        }
        
        foreach ($this->rolesScheduledForDeletions as $role){
            $this->deleteRole($conn, $role);
        }
    }
    
    protected function updateRole(Connection $conn, array $changeSet)
    {   
        $sql = 'UPDATE acl_security_identities SET identifier = ? WHERE identifier = ?';
        $conn->executeUpdate($sql, $changeSet);
    }
    
    protected function deleteRole(Connection $conn, $role)
    {
        $conn->delete('acl_security_identities', array('identifier' => $role));
    }
    
    
    public function getSubscribedEvents()
    {
        return array(Events::onFlush, Events::postFlush);
    }
}