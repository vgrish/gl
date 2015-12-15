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

		$default = $this->getProperty('default', 'false');
		if ($default == 'true') {
			$this->setProperties(array(
				'default' => 1,
				'identifier' => 1,
				'class' => 'glCity',
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
			'class' => $class,
			'id:!=' => $id
		))
		) {
			$this->modx->error->addField('identifier', $this->modx->lexicon('gl_err_ae'));
		}

		return parent::beforeSet();
	}

	/** {@inheritDoc} */
	public function afterSave()
	{
		$active = $this->getProperty('active', 0);

		$class = trim($this->getProperty('class'));
		$identifier = trim($this->getProperty('identifier'));

		if (
			!empty($class) AND
			$object = $this->modx->getObject($class, $identifier)
		) {
			$object->set('active', $active);
			$object->save();
		}

		return true;
	}

}

return 'modglDataUpdateProcessor';
