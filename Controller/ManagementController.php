<?php
namespace Neutron\UserBundle\Controller;

use Neutron\UserBundle\Entity\Admin;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\RedirectResponse;

use FOS\UserBundle\Model\UserInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagementController extends Controller
{
    public function indexAction()
    {
        $userManagementGrid = 
            $this->get('neutron.datagrid')->get('user_management');
        
        return $this->render('NeutronUserBundle:Management:index.html.twig', array(
            'userManagementGrid' => $userManagementGrid       
        ));
    }
    
    public function addAction()
    {
        
        $form = $this->container->get('neutron_user.management.form');
        $formHandler = $this->container->get('neutron_user.management.form.handler');
        
        $process = $formHandler->process($this->get('fos_user.user_manager')->createUser());
        
        if ($process instanceof Response){
            return $process;
        }
        
        return $this->render('NeutronUserBundle:Management:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function editAction($rowId)
    {
        $user = $this->get('fos_user.user_manager')->findUserBy(array('id' => $rowId));
        
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new \InvalidArgumentException('User not found');
        }
        
        $form = $this->container->get('neutron_user.management.form');
        $formHandler = $this->container->get('neutron_user.management.form.handler');
        
        $process = $formHandler->process($user);
        
        if ($process instanceof Response){
            return $process;
        }

        return $this->render('NeutronUserBundle:Management:edit.html.twig', array(
            'form' => $form->createView()  
        ));
    }
    
    public function deleteAction($rowId)
    {
        $user = $this->get('fos_user.user_manager')->findUserBy(array('id' => $rowId));
        
        if (!is_object($user) || !$user instanceof UserInterface 
                || $user->getRole() === 'ROLE_SUPER_ADMIN' 
                || $user == $this->get('security.context')->getToken()->getUser()) {
            throw new \InvalidArgumentException('User not found');
        }
        
        if ($this->getRequest()->getMethod() == 'POST' && $this->getRequest()->get('delete', false)){
            $this->get('fos_user.user_manager')->deleteUser($user);
            
            $this->get('session')->getFlashBag()
                ->add('neutron_user_management_success', 'user.flash.deleted');
            
            $notificationEnabled = $this->container->getParameter('neutron_user.management.notification.enabled');
            
            if ($notificationEnabled){
                $this->get('fos_user.mailer')->sendDeleteUserEmailMessage($user);
            }
            
            return new RedirectResponse($this->get('router')->generate('neutron_user_management'));
        }

        return $this->render('NeutronUserBundle:Management:delete.html.twig', array(
            'user' => $user
        ));
    }
        
}