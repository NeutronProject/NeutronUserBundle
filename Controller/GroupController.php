<?php
namespace Neutron\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Neutron\Bundle\DataGridBundle\DataGrid\Provider\ContainerAwareProvider;

use Symfony\Bundle\TwigBundle\TwigEngine;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\DependencyInjection\ContainerAware;

class GroupController extends ContainerAware
{
    public function indexAction()
    {
        $datagrid = $this->container->get('neutron.datagrid')->get('group_management');
        
        $template = $this->container->get('templating')
            ->render('NeutronUserBundle:Group:index.html.twig', array('datagrid' => $datagrid));
        
        return new Response($template);
    }
    
    public function manipulateAction($id = null)
    {
        $form = $this->container->get('neutron_user.group.form');
        $formHandler = $this->container->get('neutron_user.group.form.handler');
        $manager = $this->container->get('neutron_user.group_manager');
        
        if ($id){
            $group = $manager->findGroupBy(array('id' => (int) $id));
        } else {
            $group = $manager->createGroup();
        }
        
        
        $process = $formHandler->process($group);
        
        if ($process instanceof Response){
            return $process;
        }
        
        $template = $this->container->get('templating')
            ->render('NeutronUserBundle:Group:manipulate.html.twig', array('form' => $form->createView()));
        
        return new Response($template);
    }

    
    public function deleteAction($id)
    {
        $id = (int) $id;
        $manager = $this->container->get('neutron_user.group_manager');
        $group = $manager->findGroupBy(array('id' => $id));
        
        if (!$group){
            throw new NotFoundHttpException();
        }
        
        $request = $this->container->get('request');
        
        
        if ($request->getMethod() == 'POST'){
        
            $operation = $request->get('operation', false);
        
            $validOperations = array('delete');
        
            if (!in_array($operation, $validOperations)){
                throw new \InvalidArgumentException(sprintf('Operation "%s" is not valid', $operation));
            }
        
            $manager->deleteGroup($group);
        
        
            $request->getSession()
                ->getFlashBag()->add('neutron_user_group_success', 'group.flash.deleted');
        
            $url = $this->container->get('router')->generate('neutron_user_group_management');
        
            return new RedirectResponse($url);
        }
        
        $template = $this->container->get('templating')
            ->render('NeutronUserBundle:Group:delete.html.twig', array('record' => $group));
        
        return new Response($template);
    }
}