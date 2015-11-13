<?php

/**
 * Remove a glData
 */
class modglDataRemoveProcessor extends modObjectRemoveProcessor
{
	public $classKey = 'glData';
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

	/** {@inheritDoc} */
	public function beforeRemove()
	{
		if ($this->object->get('default')) {
			$this->failure($this->modx->lexicon('gl_err_lock'));
		}
		return parent::beforeRemove();
	}

}

return 'modglDataRemoveProcessor';