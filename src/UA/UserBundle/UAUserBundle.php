<?php

namespace UA\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class UAUserBundle extends Bundle
{

	public function getParent() {
		return 'FOSUserBundle';
	}
}
