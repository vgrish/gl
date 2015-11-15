<?php

/**
 * Create an glRegion
 */
class modglRegionCreateProcessor extends modObjectCreateProcessor
{
	public $objectType = 'glRegion';
	public $classKey = 'glRegion';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		return parent::beforeSet();
	}

}

return 'modglRegionCreateProcessor';