<?php
namespace Neutron\UserBundle\Model;

interface GroupManagerInterface
{
    public function createGroup();
    
    public function updateGroup(GroupInterface $group);
    
    public function deleteGroup(GroupInterface $group);
    
    public function findGroupBy(array $criteria);
    
    public function findGroups();
}