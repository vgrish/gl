<?php

abstract class glPlugin
{
    /** @var modX $modx */
    protected $modx;
    /** @var gl $gl */
    protected $gl;
    /** @var array $scriptProperties */
    protected $scriptProperties;

    public function __construct($modx, &$scriptProperties)
    {
        $this->scriptProperties =& $scriptProperties;
        $this->modx = $modx;
        $this->gl = $this->modx->gl;

        if (!$this->gl) {
            return false;
        }
    }

    abstract public function run();
}