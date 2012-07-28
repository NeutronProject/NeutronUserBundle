<?php
namespace Neutron\UserBundle\Model;

interface RoleManagerInterface 
{
    public function createRole();
    
    public function updateRole(RoleInterface $role);
    
    public function deleteRole(RoleInterface $role);
    
    public function findRoleBy(array $criteria);
    
    public function getRoles();
    
    public function getAvailableRoles();


}