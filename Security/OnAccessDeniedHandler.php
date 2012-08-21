<?php
namespace Neutron\UserBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;


class OnAccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $httpKernel;
    
	private $router;

	public function __construct($httpKernel, Router $router)
	{
	    $this->httpKernel = $httpKernel;
		$this->router = $router;
	}

	public function handle(Request $request, AccessDeniedException $accessDeniedException)
	{
	   die('AccessDeniedException');
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
	    $exception = $event->getException();
	    
	    if ($exception instanceof AccessDeniedException){
	        
	        $requestedUri = $event->getRequest()->getRequestUri();
	        
	        if (preg_match('/^\/admin/', $requestedUri)){
	           return;
	        }
	        
	        $event->stopPropagation();
	        $response = $this->httpKernel->forward('AppBundle:Frontend\Default:index', array());
	        $response->setStatusCode(403);
	        $event->setResponse($response);
	    }
	 
	}
}