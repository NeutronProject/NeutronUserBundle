<?php
namespace Neutron\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;


class ProfileController extends ContainerAware
{
    /**
     * Show the user
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->container->get('templating')
            ->renderResponse('NeutronUserBundle:Profile:show.html.twig', array('user' => $user));
    }

    /**
     * Edit the user
     */
    public function editAction()
    {  
        $user = $this->container->get('security.context')->getToken()->getUser();
        
        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('fos_user.profile.form');
        $formHandler = $this->container->get('fos_user.profile.form.handler');

        $process = $formHandler->process($user);
        
        if ($process instanceof Response){
            return $process;
        }

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:edit.html.twig',
            array('form' => $form->createView())
        );
    }
    
}
