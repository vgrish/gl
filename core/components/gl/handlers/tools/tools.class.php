<?php


interface glSystemToolsInterface
{

	/** @inheritdoc} */
	public function getOption($key, $config = array(), $default = null);

	/** @inheritdoc} */
	public function failure($message = '', $data = array(), $placeholders = array());

	/** @inheritdoc} */
	public function success($message = '', $data = array(), $placeholders = array());

	/** @inheritdoc} */
	public function getFileContent($classKey = '');


}

class Tools implements glSystemToolsInterface
{

	/** @var modX $modx */
	protected $modx;
	/** @var gl $gl */
	protected $gl;
	/** @var array $config */
	protected $config = array();


	public function __construct($gl, $config)
	{
		$this->gl = &$gl;
		$this->modx = &$gl->modx;
		$this->config =& $config;

	}

	/** @inheritdoc} */
	public function getOption($key, $config = array(), $default = null)
	{
		return $this->gl->getOption($key, $config, $default);
	}

	/** @inheritdoc} */
	public function failure($message = '', $data = array(), $placeholders = array())
	{
		return $this->gl->failure($message, $data, $placeholders);
	}

	/** @inheritdoc} */
	public function success($message = '', $data = array(), $placeholders = array())
	{
		return $this->gl->success($message, $data, $placeholders);
	}

	public function getFileContent($classKey = '')
	{
		$filePath = $this->config['sypexgeoPath'] . 'info/';

		switch ($classKey) {
			case 'glCountry':
				$filePath .= 'country.tsv';
				break;
			case 'glRegion':
				$filePath .= 'region.tsv';
				break;
			case 'glCity':
				$filePath .= 'city.tsv';
				break;

		}

		return file_get_contents($filePath);
	}

}