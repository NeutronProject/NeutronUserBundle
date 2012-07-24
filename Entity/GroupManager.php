<?php
namespace Neutron\UserBundle\Entity;

use Neutron\UserBundle\Model\GroupInterface;

use Neutron\UserBundle\Model\GroupManagerInterface;

use Doctrine\ORM\EntityManager;


class GroupManager implements GroupManagerInterface
{
    protected $em;
    
    protected $repository;
    
    protected $class;
    
    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);
        $this->class = $em->getClassMetadata($class)->getName();
    }
    
    public function createGroup()
    {
        $class = $this->class;
        return new $class();
    }
    
    public function updateGroup(GroupInterface $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }
    
    public function deleteGroup(GroupInterface $group)
    {
        $this->em->remove($group);
        $this->em->flush();
    }
    
    public function findGroupBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria); 
    }
    
    public function findGroups()
    {
        return $this->repository->findAll();
    }
}