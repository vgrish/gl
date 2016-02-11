<?php

/**
 * Create an glData
 */
class modglDataCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'glData';
    public $classKey = 'glData';
    public $languageTopics = array('gl');
    public $permission = '';

    /** {@inheritDoc} */
    public function beforeSet()
    {
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

        if ($object = $this->modx->getObject($class, $identifier)) {
            $object->set('active', $active);
            $object->save();
        }

        return true;
    }


}

return 'modglDataCreateProcessor';