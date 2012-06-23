<?php

namespace Neutron\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NeutronUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
