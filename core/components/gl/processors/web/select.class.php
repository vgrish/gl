<?php


class modglLocationSelectProcessor extends modObjectProcessor
{
    /** @var gl $gl */
    public $gl;

    /** {@inheritDoc} */
    public function initialize()
    {
        /** @var gl $gl */
        $this->gl = $this->modx->getService('gl');
        $this->gl->initialize($this->getProperty('context', $this->modx->context->key));

        return parent::initialize();
    }

    /** {@inheritDoc} */
    public function process()
    {
        $id = (int)$this->getProperty('id');
        $class = trim($this->getProperty('class'));
        if (empty($class)) {
            return $this->modx->lexicon('gl_err_ns');
        }

        $current = $this->gl->getCurrentData($id, $class);
        if (!empty($current)) {
            $this->gl->opts['current'] = $current;
        }
        $this->gl->opts['set'] = true;

        $pls = array_merge(
            $this->gl->opts,
            array('pls' => $this->gl->flattenArray($current))
        );

        return $this->success('', $pls);
    }

}

return 'modglLocationSelectProcessor';