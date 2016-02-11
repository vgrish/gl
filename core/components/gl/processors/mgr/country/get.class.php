<?php

/**
 * Get an glCountry
 */
class modglCountryGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'glCountry';
    public $classKey = 'glCountry';
    public $languageTopics = array('gl');
    public $permission = '';

    public $gl;

    /** {@inheritDoc} */
    public function initialize()
    {
        /** @var gl $gl */
        $this->gl = $this->modx->getService('gl');
        $this->gl->initialize($this->getProperty('context', $this->modx->context->key));

        return parent::initialize();
    }

    /**
     * @return array|string
     */
    public function cleanup()
    {
        $array = $this->object->toArray();

        return $this->success('', $array);
    }

}

return 'modglCountryGetProcessor';