<?php

/**
 * Create an glCity
 */
class modglCityCreateProcessor extends modObjectCreateProcessor
{
	public $objectType = 'glCity';
	public $classKey = 'glCity';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		return parent::beforeSet();
	}

}

return 'modglCityCreateProcessor';