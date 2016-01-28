<?php

/**
 * Update an glData
 */
class modglDataUpdateProcessor extends modObjectUpdateProcessor
{
	public $objectType = 'glData';
	public $classKey = 'glData';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		if ($this->getProperty('default')) {
			$this->setProperties(array(
				'default'    => 1,
				'identifier' => 1,
				'class'      => 'glCity',
			));

			return parent::beforeSet();
		}

		$identifier = trim($this->getProperty('identifier'));
		if (empty($identifier)) {
			$this->modx->error->addField('identifier', $this->modx->lexicon('gl_err_ae'));
		}

		$class = trim($this->getProperty('class'));
		if (empty($class)) {
			$this->modx->error->addField('class', $this->modx->lexicon('gl_err_ae'));
		}

		if ($this->modx->getCount($this->classKey, array(
			'identifier' => $identifier,
			'class'      => $class,
			'id:!='      => $this->getProperty('id')
		))
		) {
			$this->modx->error->addField('identifier', $this->modx->lexicon('gl_err_ae'));
		}

		return parent::beforeSet();
	}

	/** {@inheritDoc} */
	public function afterSave()
	{
		if ($this->getProperty('default')) {
			return true;
		}

		if ($object = $this->modx->getObject(trim($this->getProperty('class')), trim($this->getProperty('identifier')))) {
			$object->set('active', $this->getProperty('active', 0));
			$object->save();
		}

		return true;
	}

}

return 'modglDataUpdateProcessor';
