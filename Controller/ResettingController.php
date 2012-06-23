<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Neutron\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Controller\ResettingController as BaseController;

/**
 * Controller managing the resetting of the password
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class ResettingController extends BaseController
{

    /**
     * Request reset user password: submit form and send email
     */
    public function sendEmailAction()
    {
        $username = $this->container->get('request')->request->get('username');

        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($username);

        if (null === $user) {
        	return new Response(json_encode(array(
        		'success' => false,
        		'error' => $this->container->get('translator')->trans('resetting.request.invalid_username', array('%username%' => $username), 'FOSUserBundle'),
        	)));
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
        	return new Response(json_encode(array(
        		'success' => false,
        		'error' => $this->container->get('translator')->trans('resetting.password_already_requested', array(), 'FOSUserBundle')
        	)));
        }

        $user->generateConfirmationToken();
        $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new Response(json_encode(array(
        	'success' => true,
        	'url' => $this->container->get('router')->generate('fos_user_resetting_check_email')
        )));

    }


    /**
     * Reset user password
     */
    public function resetAction($token)
    {
    	$user = $this->container->get('fos_user.user_manager')->findUserByConfirmationToken($token);

    	if (null === $user) {
    		throw new NotFoundHttpException(sprintf('The user with "confirmation token" does not exist for value "%s"', $token));
    	}

    	if (!$user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
    		return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request'));
    	}

    	$form = $this->container->get('fos_user.resetting.form');
    	$formHandler = $this->container->get('fos_user.resetting.form.handler');
    	$process = $formHandler->process($user);

    	if ($this->container->get('request')->isXmlHttpRequest()){

    		if ($process) {
    			$this->authenticateUser($user);

    			return new Response(json_encode(array(
    				'success' => true,
    				'url' => $this->getRedirectionUrl($user)
    			)));

    		} else {
    			return new Response(json_encode(array(
    				'success' => false,
    				'error' => $this->container->get('neutron_component.form.helper.form_helper')
    			        ->getErrorMessages($form, 'validators')
    			)));
    		}
    	}


    	return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:reset.html.'.$this->getEngine(), array(
    			'token' => $token,
    			'form' => $form->createView(),
    			'theme' => $this->container->getParameter('fos_user.template.theme'),
    	));
    }

}
