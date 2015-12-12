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

		if (!is_object($this->gl) OR !($this->gl instanceof gl)) {
			$corePath = $this->modx->getOption('gl_core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
			$this->gl = $this->modx->getService('gl', 'gl', $corePath . 'model/gl/', $this->scriptProperties);
		}
	}

	abstract public function run();
}