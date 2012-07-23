<?php
/*
 * This file is part of NeutronUserBundle
 *
 * (c) Zender <azazen09@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Neutron\UserBundle\Form\EventSubscriber;


use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\Security\Core\SecurityContext;

use Symfony\Component\Form\FormFactoryInterface;

use Symfony\Component\Form\Exception\UnexpectedTypeException;

use Symfony\Component\Form\FormEvent;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Neutron user event subscriber
 *
 * @author Nikolay Georgiev <azazen09@gmail.com>
 * @since 1.0
 */
class UserSubscriber implements EventSubscriberInterface
{

    protected $factory;
    
    protected $securityContext;

    
    public function __construct(FormFactoryInterface $factory, SecurityContext $securityContext)
    {
        $this->factory = $factory;
        $this->securityContext = $securityContext;
    }
    
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        
        if (empty($data)) {
            return;
        }
  
        if (!$data->getId()) {
            $form->remove('lastLogin');
            $form->remove('passwordRequestedAt');
        }

    }
    
    public function preBind(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if (empty($data)) {
            return;
        }

        if (!is_array($data) && !($data instanceof \Traversable && $data instanceof \ArrayAccess)) {
            throw new UnexpectedTypeException($data, 'array or (\Traversable and \ArrayAccess)');
        }
      
        
        $form->remove('lastLogin');
        $form->remove('passwordRequestedAt');
        

    }
    
    public function postBind(FormEvent $event)
    {
        $data = $event->getData();

        if (!$data instanceof UserInterface) {
            return;
        }
        
        $data->setIsAdmin(true);
    }


    /**
     * Subscription for Form Events
     */
    static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_BIND => 'preBind',
            FormEvents::POST_BIND => 'postBind',
        );
    }
}