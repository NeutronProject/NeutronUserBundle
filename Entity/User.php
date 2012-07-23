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
     * @var boolean 
     *
     * @ORM\Column(type="boolean", name="is_admin")
     */
    protected $isAdmin = false;
    
    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="neutron_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
     
    public function setIsAdmin($bool)
    {
        $this->isAdmin = (bool) $bool;
    }
    
    public function isAdmin()
    {
        return $this->isAdmin;
    }
    

    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }
    
        
}
