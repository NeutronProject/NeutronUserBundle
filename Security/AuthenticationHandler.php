<?php
namespace Neutron\UserBundle\Security;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationHandler
	implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
	private $router;

	public function __construct(Router $router)
	{
		$this->router = $router;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
	    
	    $targetPath = $request->getSession()->get('_security.target_path');
	    if ($targetPath) {
	        $url = $targetPath;
	    } else {
	        // Otherwise, redirect him to wherever you want
	        
	    }
	    
		if ($request->isXmlHttpRequest()) {
            return new Response(json_encode(array(
				'success' => true,
				'username' => $token->getUser()->getUsername(),
				'url' => $url
			)));
		} else {
		    $request->getSession()->getFlashBag()->add('user.flash.login.success', 'user.flash.login.success');
		    return new RedirectResponse($url);
		}
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		if ($request->isXmlHttpRequest()) {
			return new Response(json_encode(array(
				'success' => false,
				'error' => $exception->getMessage()
			)));
		} else {
			// Create a flash message with the authentication error message
			$request->getSession()->getFlashBag()->add('user.flash.login.failure', $exception->getMessage());
			
			$url = $this->router->generate('fos_user_security_login');

			return new RedirectResponse($url);
		}
	}
}