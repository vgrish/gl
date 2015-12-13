<?php

interface FormatInterface
{
	/** @inheritdoc} */
	public function processData(array $data = array());
}

class Format implements FormatInterface
{

	/** @var modX $modx */
	protected $modx;
	/** @var gl $gl */
	protected $gl;
	/** @var array $config */
	protected $config = array();
	/** @var string $namespace */
	protected $namespace;

	/**
	 * @param $modx
	 * @param $config
	 */
	public function __construct($modx, &$config)
	{
		$this->modx = $modx;
		$this->config =& $config;
		$this->gl = $this->modx->gl;

		if (!is_object($this->gl) OR !($this->gl instanceof gl)) {
			$corePath = $this->modx->getOption('gl_core_path', null, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
			$this->gl = $this->modx->getService('gl', 'gl', $corePath . 'model/gl/', $this->config);
		}

		$this->namespace = $this->gl->namespace;
	}


	/**
	 * @param $n
	 * @param array $p
	 */
	public function __call($n, array$p)
	{
		echo __METHOD__ . ' says: ' . $n;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function processData(array $data = array())
	{
		while (list($key, $val) = each($data)) {
			if (!is_string($val) OR !is_array($val)) {
				continue;
			}
			$formatMethod = 'format' . ucfirst(str_replace('_', '', $key));
			if (!method_exists($this, $formatMethod)) {
				continue;
			}
			$val = $this->$formatMethod($val);

			if (is_array($val)) {
				$data[$key] = $this->processData($val);
			} else {
				$data[$key] = $val;
			}
		}
		return $data;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function formatData(array $data = array())
	{
		$data['resource_url'] = $data['resource'];
		return $data;
	}

	/**
	 * @param int $resource
	 * @return string
	 */
	public function formatResourceUrl($resource = 0)
	{
		$url = '';
		$args = array();
		if (!empty($resource)) {
			$url = $this->modx->makeUrl($resource, '', $args, 'full', array('xhtml_urls' => false));
		}
		return $url;
	}

}