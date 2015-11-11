<?php

/**
 * Remove a glCity
 */
class modglCityRemoveProcessor extends modObjectRemoveProcessor
{
	public $classKey = 'glCity';
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

return 'modglCityRemoveProcessor';