<?php

/**
 * Update an glCity
 */
class modglCityUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'glCity';
    public $classKey = 'glCity';
    public $languageTopics = array('gl');
    public $permission = '';

    /** {@inheritDoc} */
    public function beforeSet()
    {
        $name_ru = trim($this->getProperty('name_ru'));
        if (empty($name_ru)) {
            $this->modx->error->addField('name_ru', $this->modx->lexicon('gl_err_ae'));
        }

        if ($this->getProperty('default')) {
            $this->setProperty('active', 1);
        }

        return parent::beforeSet();
    }

}

return 'modglCityUpdateProcessor';
