<?php
namespace Neutron\UserBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

use Neutron\UserBundle\Model\RoleInterface;

/**
 * @ORM\Table(name="neutron_role")
 * @ORM\Entity
 */
class Role implements RoleInterface
{
    /**
     * @var integer 
     *
     * @ORM\Id @ORM\Column(name="id", type="integer")
     * 
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="name", length=255, nullable=false, unique=false)
     */
    protected $name;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="role", length=35, nullable=false, unique=true)
     */
    protected $role;
    
    /**
     * @var boolean 
     *
     * @ORM\Column(type="boolean", name="enabled")
     */
    protected $enabled = false;
   
    public function getId()
    {
        return $this->id;
    }
    
    public function setName($name)
    {
        $this->name = (string) $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setRole($role)
    {
        $this->role = (string) $role;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function setEnabled($bool)
    {
        $this->enabled = (bool) $bool;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }
}