<?php
namespace Neutron\UserBundle\Mailer;

use FOS\UserBundle\Mailer\Mailer;

use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Mailer\TwigSwiftMailer as BaseMailer;
use FOS\UserBundle\Model\UserInterface;
use Neutron\UserBundle\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class TwigSwiftMailer extends BaseMailer implements MailerInterface 
{
    protected $securityContext;

    public function __construct(\Swift_Mailer $mailer, 
            UrlGeneratorInterface $router, 
            \Twig_Environment $twig,
            SecurityContext $securityContext,
            array $parameters)
    {
        parent::__construct($mailer, $router, $twig, $parameters);
        
        $this->securityContext = $securityContext;
    
    }

    public function sendAddUserEmailMessage(UserInterface $user, $plainPassword)
    {
        if (!$this->parameters['management']['notification_enabled']){
            return false;
        }
        
        $admin = $this->securityContext->getToken()->getUser();
        $template = $this->parameters['management']['template']['notification']['add'];
        $url = $this->router->generate('fos_user_security_login', array(), true);
        $context = array(
            'user' => $user,
            'plainPassword' => $plainPassword,
            'loginUrl' => $url
        );
        
        $this->sendMessage($template, $context, $admin->getEmail(), $user->getEmail());
    }
    
    public function sendEditUserEmailMessage(UserInterface $user, $plainPassword)
    {
        $admin = $this->securityContext->getToken()->getUser();
        $template = $this->parameters['management']['template']['notification']['edit'];
        
        $context = array(
            'user' => $user,
            'plainPassword' => $plainPassword,
        );
        
        $this->sendMessage($template, $context, $admin->getEmail(), $user->getEmail());
    }
    
    public function sendDeleteUserEmailMessage(UserInterface $user)
    {
        $admin = $this->securityContext->getToken()->getUser();
        $template = $this->parameters['management']['template']['notification']['delete'];
        
        $context = array(
            'user' => $user
        );
        
        $this->sendMessage($template, $context, $admin->getEmail(), $user->getEmail());
    }
    

}
