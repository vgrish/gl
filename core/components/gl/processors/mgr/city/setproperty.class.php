<?php

require_once dirname(__FILE__) . '/update.class.php';

/**
 * SetProperty a glCity
 */
class modglCitySetPropertyProcessor extends modglCityUpdateProcessor
{
	/** @var glCity $object */
	public $object;
	public $objectType = 'glCity';
	public $classKey = 'glCity';
	public $languageTopics = array('gl');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		$fieldName = $this->getProperty('field_name', null);
		$fieldValue = $this->getProperty('field_value', null);
		$this->properties = $this->object->toArray();
		if (!is_null($fieldName) AND !is_null($fieldValue)) {
			$this->setProperty($fieldName, $fieldValue);
		}

		return parent::beforeSet();
	}
}

return 'modglCitySetPropertyProcessor';