<?php

/**
 * Remove a glCountry
 */
class modglCountryRemoveProcessor extends modObjectRemoveProcessor
{
	public $classKey = 'glCountry';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function initialize()
	{
		if (!$this->modx->hasPermission($this->permission)) {
			return $this->modx->lexicon('access_denied');
		}
		return parent::initialize();
	}

}

return 'modglCountryRemoveProcessor';