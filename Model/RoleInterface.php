<?php
namespace Neutron\UserBundle\Model;

use Symfony\Component\Security\Core\Role\RoleInterface as BaseRole;

interface RoleInterface extends BaseRole 
{
    public function setName($name);
    
    public function getName();
    
    public function setEnabled($bool);
    
    public function isEnabled();
}