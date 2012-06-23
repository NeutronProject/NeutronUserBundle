<?php
namespace Neutron\UserBundle\Mailer;

use FOS\UserBundle\Model\UserInterface;

use FOS\UserBundle\Mailer\MailerInterface as BaseMailerInterface;

interface MailerInterface extends BaseMailerInterface 
{
    public function sendAddUserEmailMessage(UserInterface $user, $plainPassword);
    
    public function sendEditUserEmailMessage(UserInterface $user, $plainPassword);
    
    public function sendDeleteUserEmailMessage(UserInterface $user);
}