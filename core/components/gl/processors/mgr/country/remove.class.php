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
	public function beforeRemove()
	{
		if ($this->object->get('default')) {
			return $this->modx->lexicon('gl_err_lock');
		}
		return parent::beforeRemove();
	}

}

return 'modglCountryRemoveProcessor';