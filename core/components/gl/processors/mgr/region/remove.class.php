<?php

/**
 * Remove a glRegion
 */
class modglRegionRemoveProcessor extends modObjectRemoveProcessor
{
	public $classKey = 'glRegion';
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

return 'modglRegionRemoveProcessor';