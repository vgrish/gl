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
	public function beforeRemove()
	{
		if ($this->object->get('default')) {
			return $this->modx->lexicon('gl_err_lock');
		}

		return parent::beforeRemove();
	}

}

return 'modglRegionRemoveProcessor';