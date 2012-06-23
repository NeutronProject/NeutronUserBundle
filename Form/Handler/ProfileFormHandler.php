<?php
namespace Neutron\UserBundle\Form\Handler;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\Response;

use Neutron\ComponentBundle\Form\Helper\FormHelper;

use Neutron\UserBundle\Form\Model\ChangePassword;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Form\Model\CheckPassword;

class ProfileFormHandler
{
    protected $request;
    protected $router;
    protected $userManager;
    protected $form;
    protected $formHelper;

    public function __construct(Form $form, FormHelper $formHelper, 
            Request $request, Router $router, UserManagerInterface $userManager)
    {
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->userManager = $userManager;
    }

    public function process(UserInterface $user)
    {  
        $this->form->setData(array('user' => $user));

        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bindRequest($this->request);

            if ($this->form->isValid()) {
                
                $this->onSuccess($user);
                $this->request->getSession()
                    ->getFlashBag()->add('neutron_user.profile.success', 'profile.flash.updated');
                
                $result = array(
                    'success' => true,
                    'redirect_uri' => $this->router->generate('fos_user_profile_show')
                );
                
            } else {
                $result = array(
                    'success' => false,
                    'errors' => $this->formHelper->getErrorMessages($this->form, 'NeutronUserBundle')
                );
            }

            // Reloads the user to reset its username. This is needed when the
            // username or password have been changed to avoid issues with the
            // security layer.
            $this->userManager->reloadUser($user);
            
            return new Response(json_encode($result));
        }

       
    }

    protected function onSuccess(UserInterface $user)
    {
        $this->userManager->updateUser($user);
    }
}
