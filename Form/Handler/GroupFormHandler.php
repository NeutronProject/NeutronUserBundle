<?php
namespace Neutron\UserBundle\Form\Handler;

use Neutron\UserBundle\Model\GroupInterface;

use Neutron\UserBundle\Model\GroupManagerInterface;

use Neutron\ComponentBundle\Form\Helper\FormHelper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Form;

use Symfony\Component\HttpFoundation\Request;



class GroupFormHandler
{
    protected $request;
    protected $router;
    protected $manager;
    protected $form;
    protected $formHelper;

    public function __construct(Form $form, FormHelper $formHelper, 
            Request $request, Router $router, GroupManagerInterface $manager)
    {
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->manager = $manager;
    }

    public function process(GroupInterface $group)
    {
        $this->form->setData($group);
        
        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bindRequest($this->request);
            
            if ($this->form->isValid()) {
                $this->onSuccess($group);
                $this->request->getSession()
                    ->getFlashBag()->add('neutron_user_group_success', 'user.flash.updated');
                
                $result = array(
                    'success' => true,
                    'redirect_uri' => $this->router->generate('neutron_user_group_management')
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

    protected function onSuccess(GroupInterface $group)
    {
        $this->manager->updateGroup($group);
    }
   
}
