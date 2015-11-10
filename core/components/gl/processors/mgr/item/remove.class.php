<?php

/**
 * Remove an Items
 */
class glItemRemoveProcessor extends modObjectProcessor {
	public $objectType = 'glItem';
	public $classKey = 'glItem';
	public $languageTopics = array('gl');
	//public $permission = 'remove';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('gl_item_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var glItem $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('gl_item_err_nf'));
			}

			$object->remove();
		}

		return $this->success();
	}

}

return 'glItemRemoveProcessor';