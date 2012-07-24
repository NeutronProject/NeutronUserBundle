<?php
namespace Neutron\UserBundle\Entity;

use Neutron\UserBundle\Model\GroupInterface;

use Neutron\UserBundle\Model\RoleInterface;

use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="neutron_group")
 */
class Group implements GroupInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string 
     *
     * @ORM\Column(type="string", name="name", length=35, nullable=false, unique=true)
     */
    protected $name;
    
    /**
     * @var boolean 
     *
     * @ORM\Column(type="boolean", name="enabled")
     */
    protected $enabled = false;
    
    /**
     * @ORM\ManyToMany(targetEntity="Role")
     */
    private $roles;
 
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }
    
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
    
    public function setEnabled($bool)
    {
        $this->enabled = (bool) $bool;
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }
    

    public function addRole(RoleInterface $role)
    {
        if (!$this->hasRole($role)) {
            $this->roles->add($role);
        }
    
        return $this;
    }

    public function hasRole(RoleInterface $role)
    {
        return $this->roles->contains($role);
    }
    
    public function getRoles()
    {
        $roles = new ArrayCollection();
        
        foreach ($this->roles as $role){
            if ($role->isEnabled()){
                $roles->add($role);
            }
        }
        
        return $roles;
    }
    
    public function removeRole(RoleInterface $role)
    {
        if ($this->hasRole($role)){
            $this->roles->removeElement($role);
        }
    
        return $this;
    }
    
    
    public function setRoles(array $roles)
    {
        $this->roles = new ArrayCollection();
        
        foreach ($roles as $role){
            $this->addRole($role);
        }
    
        return $this;
    }
}