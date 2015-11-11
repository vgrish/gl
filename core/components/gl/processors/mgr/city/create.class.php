<?php

/**
 * Create an glCity
 */
class modglCityCreateProcessor extends modObjectCreateProcessor
{
	public $objectType = 'glCity';
	public $classKey = 'glCity';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		$host = trim($this->getProperty('host'));
		if (empty($host)) {
			$this->modx->error->addField('host', $this->modx->lexicon('gl_err_ae'));
		}

		if ($this->modx->getCount($this->classKey, array(
			'host' => $host
		))
		) {
			$this->modx->error->addField('host', $this->modx->lexicon('gl_err_ae'));
		}

		return parent::beforeSet();
	}

}

return 'modglCityCreateProcessor';