<?php
namespace Neutron\UserBundle\Form\Handler;

use Neutron\ComponentBundle\Form\Helper\FormHelper;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

use Symfony\Component\HttpFoundation\Response;

use Neutron\UserBundle\Mailer\MailerInterface;

use Symfony\Component\Form\Form;

use Symfony\Component\HttpFoundation\Request;

use FOS\UserBundle\Model\UserInterface;

use FOS\UserBundle\Model\UserManagerInterface;


class ManagementFormHandler
{
    protected $request;
    protected $router;
    protected $userManager;
    protected $form;
    protected $formHelper;
    protected $mailer;

    public function __construct(Form $form, FormHelper $formHelper, 
            Request $request, Router $router, UserManagerInterface $userManager, 
            MailerInterface $mailer)
    {
        $this->form = $form;
        $this->formHelper = $formHelper;
        $this->request = $request;
        $this->router = $router;
        $this->userManager = $userManager;
        $this->mailer = $mailer;
    }

    public function process(UserInterface $user = null)
    {
        $this->form->setData(array('user' => $user));
        
        if ($this->request->isXmlHttpRequest()) {
            
            $this->form->bindRequest($this->request);
            
            if ($this->form->isValid()) {
                $this->onSuccess($user);
                $this->request->getSession()
                    ->getFlashBag()->add('neutron_user_management_success', 'user.flash.updated');
                
                $result = array(
                    'success' => true,
                    'redirect_uri' => $this->router->generate('neutron_user_management')
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

    protected function onSuccess(UserInterface $user)
    {
        $id = $user->getId();
        $plainPassword = $user->getPlainPassword();
        
        $this->userManager->updateUser($user);
        
        // is updating user?
        if ($id){
            $this->mailer->sendEditUserEmailMessage($user, $plainPassword);
        } else {
            $this->mailer->sendAddUserEmailMessage($user, $plainPassword);
        }
    }
   
}
