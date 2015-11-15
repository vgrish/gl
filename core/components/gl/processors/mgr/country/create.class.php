<?php

/**
 * Create an glCountry
 */
class modglCountryCreateProcessor extends modObjectCreateProcessor
{
	public $objectType = 'glCountry';
	public $classKey = 'glCountry';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		return parent::beforeSet();
	}

}

return 'modglCountryCreateProcessor';