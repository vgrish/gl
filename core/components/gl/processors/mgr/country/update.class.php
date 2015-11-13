<?php

/**
 * Update an glCountry
 */
class modglCountryUpdateProcessor extends modObjectUpdateProcessor
{
	public $objectType = 'glCountry';
	public $classKey = 'glCountry';
	public $languageTopics = array('gl');
	public $permission = '';


	/** {@inheritDoc} */
	public function beforeSave()
	{
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}

	/** {@inheritDoc} */
	public function beforeSet()
	{
		$id = (int)$this->getProperty('id');
		if (empty($id)) {
			return $this->modx->lexicon('gl_err_ns');
		}

		$name_ru = trim($this->getProperty('name_ru'));
		if (empty($name_ru)) {
			$this->modx->error->addField('name_ru', $this->modx->lexicon('gl_err_ae'));
		}

		return parent::beforeSet();
	}
}

return 'modglCountryUpdateProcessor';
