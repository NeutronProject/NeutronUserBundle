<?php
namespace Neutron\UserBundle\Model;

class BackendRoles
{
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    
    const ROLE_ADMIN = 'ROLE_ADMIN';
    
    const ROLE_MODERATOR = 'ROLE_MODERATOR';
    
    public static function getRoles()
    {
        return array(
            self::ROLE_MODERATOR => self::ROLE_MODERATOR,
            self::ROLE_ADMIN => self::ROLE_ADMIN,        
        );
    }
    
    public static function getAdministrativeRoles()
    {
        return array(
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN        
        );
    }
}