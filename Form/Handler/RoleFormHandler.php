<?php
namespace Neutron\UserBundle\Form\Handler;

use Neutron\UserBundle\Model\RoleInterface;

use Neutron\UserBundle\Model\RoleManagerInterface;

use Neutron\ComponentBundle\Form\Helper\FormHelper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Form;

use Symfony\Component\HttpFoundation\Request;



class RoleFormHandler
{
    protected $request;
    protected $router;
    protected $roleManager;
    protected $form;
    protected $formHelper;

    public function __construct(Form $form, FormHelper $formHelper, 
            Request $request, Router $router, RoleManagerInterface $roleManager)
    {
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->roleManager = $roleManager;
    }

    public function process(RoleInterface $role)
    {
        $this->form->setData($role);
        
        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bindRequest($this->request);
            
            if ($this->form->isValid()) {
                $this->onSuccess($role);
                $this->request->getSession()
                    ->getFlashBag()->add('neutron_user_role_success', 'user.flash.updated');
                
                $result = array(
                    'success' => true,
                    'redirect_uri' => $this->router->generate('neutron_user_role_management')
                );
                
            } else {
                $result = array(
                    'success' => false,
                    'errors' => $this->formHelper->getErrorMessages($this->form, 'NeutronUserBundle')
                );
            }
            
            return new Response(json_encode($result));

        }

        return false;
    }

    protected function onSuccess(RoleInterface $role)
    {
        $this->roleManager->updateRole($role);
    }
   
}
