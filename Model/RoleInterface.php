<?php
namespace Neutron\UserBundle\Model;

use Symfony\Component\Security\Core\Role\RoleInterface as BaseRole;

interface RoleInterface extends BaseRole 
{
    
    public function enable($bool);
    
    public function isEnabled();
}