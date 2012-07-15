<?php
namespace Neutron\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="neutron_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="role", length=255, nullable=true, unique=false)
     */
    protected $role;
    
    public function setRole($role)
    {
        $this->role = (string) $role;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }
        
}
