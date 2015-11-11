<?php

/**
 * Create an glRegion
 */
class modglRegionCreateProcessor extends modObjectCreateProcessor
{
	public $objectType = 'glRegion';
	public $classKey = 'glRegion';
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

return 'modglRegionCreateProcessor';