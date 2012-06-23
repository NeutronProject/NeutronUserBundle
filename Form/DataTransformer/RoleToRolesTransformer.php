<?php

namespace Neutron\UserBundle\Form\DataTransformer;

use Neutron\UserBundle\Entity\User;

use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class RoleToRolesTransformer implements DataTransformerInterface
{


    public function transform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (!is_array($value)) {
            throw new UnexpectedTypeException($value, 'array');
        }
        
        return $value[0];
    }

    public function reverseTransform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }
        
        return array($value);
    }
}
