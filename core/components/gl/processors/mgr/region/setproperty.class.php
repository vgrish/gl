<?php

require_once dirname(__FILE__) . '/update.class.php';

/**
 * SetProperty a glRegion
 */
class modglRegionSetPropertyProcessor extends modglRegionUpdateProcessor
{
    /** @var glRegion $object */
    public $object;
    public $objectType = 'glRegion';
    public $classKey = 'glRegion';
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

return 'modglRegionSetPropertyProcessor';