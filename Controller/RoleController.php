<?php
namespace Neutron\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\UserBundle\Entity\RoleManager;

use Neutron\Bundle\DataGridBundle\DataGrid\Provider\ContainerAwareProvider;

use Symfony\Bundle\TwigBundle\TwigEngine;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class RoleController extends ContainerAware
{
    public function indexAction()
    {
        $datagrid = $this->container->get('neutron.datagrid')->get('role_management');
        
        $template = $this->container->get('templating')
            ->render('NeutronUserBundle:Role:index.html.twig', array('datagrid' => $datagrid));
        return new Response($template);
    }
    
    public function manipulateAction($id = null)
    {
        $form = $this->container->get('neutron_user.role.form');
        $formHandler = $this->container->get('neutron_user.role.form.handler');
        $manager = $this->container->get('neutron_user.role_manager');
        
        if ($id){
            $role = $manager->findRoleBy(array('id' => (int) $id));
        } else {
            $role = $manager->createRole();
        }
        
        
        $process = $formHandler->process($role);
        
        if ($process instanceof Response){
            return $process;
        }
        
        $template = $this->container->get('templating')
            ->render('NeutronUserBundle:Role:manipulate.html.twig', array('form' => $form->createView()));
        
        return new Response($template);
    }

    
    public function deleteAction($id)
    {
        $id = (int) $id;
        $manager = $this->container->get('neutron_user.role_manager');
        $role = $manager->findRoleBy(array('id' => $id));
        
        if (!$role){
            throw new NotFoundHttpException();
        }
        
        $request = $this->container->get('request');
        
        
        if ($request->getMethod() == 'POST'){
        
            $operation = $request->get('operation', false);
        
            $validOperations = array('delete');
        
            if (!in_array($operation, $validOperations)){
                throw new \InvalidArgumentException(sprintf('Operation "%s" is not valid', $operation));
            }
        
            $manager->deleteRole($role);
        
        
            $request->getSession()
                ->getFlashBag()->add('neutron_user_role_success', 'tree.flash.deleted');
        
            $url = $this->container->get('router')->generate('neutron_user_role_management');
        
            return new RedirectResponse($url);
        }
        
        $template = $this->container->get('templating')
            ->render('NeutronUserBundle:Role:delete.html.twig', array('record' => $role));
        
        return new Response($template);
    }
}