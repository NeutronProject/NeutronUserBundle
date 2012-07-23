<?php
namespace Neutron\UserBundle\Model;

interface GroupInterface
{
    public function addRole(RoleInterface $role);

    public function getId();

    public function getName();

    public function hasRole(RoleInterface $role);

    public function getRoles();

    public function removeRole(RoleInterface $role);

    public function setName($name);

    public function setRoles(array $roles);
    
    public function setEnabled($bool);
    
    public function isEnabled();
}