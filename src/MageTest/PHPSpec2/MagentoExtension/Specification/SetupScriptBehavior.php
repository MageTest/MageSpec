<?php

namespace MageTest\PHPSpec2\MagentoExtension\Specification;

class SetupScriptBehavior
{ 
	public function afterApplyAllUpdates($spec)
	{
        $spec();
	}
}