<?php

/**
 * Create an Item
 */
class glItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'glItem';
	public $classKey = 'glItem';
	public $languageTopics = array('gl');
	//public $permission = 'create';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$name = trim($this->getProperty('name'));
		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('gl_item_err_name'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
			$this->modx->error->addField('name', $this->modx->lexicon('gl_item_err_ae'));
		}

		return parent::beforeSet();
	}

}

return 'glItemCreateProcessor';