<?php
namespace Neutron\UserBundle\Entity;

use Neutron\UserBundle\Model\RoleInterface;

use Doctrine\ORM\EntityManager;

use Neutron\UserBundle\Model\RoleManagerInterface;

class RoleManager implements RoleManagerInterface
{
    protected $em;
    
    /**
     * @var 
     */
    protected $repository;
    
    protected $class;
    
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->getName();
    }
    
    public function createRole()
    {
        $class = $this->class;
        return new $class();
    }
    
    public function updateRole(RoleInterface $role)
    {
        $this->em->persist($role);
        $this->em->flush();
    }
    
    public function deleteRole(RoleInterface $role)
    {
        $this->em->remove($role);
        $this->em->flush();
    }
    
    public function findRoleBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria); 
    }
    
    public function getRoles()
    {
        return $this->repository->findBy(array('enabled' => true));
    }
    
    public function getAvailableRoles()
    {
        $roles = array();
        foreach ($this->repository->findAll() as $role){
            $roles[] = $role->getRole();
        }
        
        return $roles;
    }
    
}