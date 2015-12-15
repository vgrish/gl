<?php

require_once dirname(__FILE__) . '/update.class.php';

/**
 * SetProperty a glData
 */
class modglDataSetPropertyProcessor extends modglDataUpdateProcessor
{
	/** @var glData $object */
	public $object;
	public $objectType = 'glData';
	public $classKey = 'glData';
	public $languageTopics = array('extras');
	public $permission = '';

	/** {@inheritDoc} */
	public function beforeSet()
	{
		$fieldName = $this->getProperty('field_name', null);
		$fieldValue = $this->getProperty('field_value', null);
		$this->properties = $this->object->toArray();
		if (!is_null($fieldName) && !is_null($fieldValue)) {
			$this->setProperty($fieldName, $fieldValue);
		}

		return parent::beforeSet();
	}

}

return 'modglDataSetPropertyProcessor';