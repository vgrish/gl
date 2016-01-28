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
	public function beforeRemove()
	{
		if ($this->object->get('default')) {
			return $this->modx->lexicon('gl_err_lock');
		}

		return parent::beforeRemove();
	}

}

return 'modglCityRemoveProcessor';