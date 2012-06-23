<?php
namespace Neutron\UserBundle\Security;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use FOS\UserBundle\Model\UserInterface;

class InteractiveLoginListener
{
    public function __construct()
    {}
    
	public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
	{
		$user = $event->getAuthenticationToken()->getUser();

		if ($user instanceof UserInterface) {
			// do something here
		}
	}
}