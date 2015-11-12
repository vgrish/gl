<?php

/**
 * The base class for gl.
 */
class gl
{
	/* @var modX $modx */
	public $modx;
	/** @var string $namespace */
	public $namespace = 'location';
	/* @var array The array of config */
	public $config = array();
	/** @var array $initialized */
	public $initialized = array();
	/** @var array $opts */
	public $opts = array();

	/** @var Tools $Tools */
	public $Tools;
	/** @var SxGeo $SxGeo */
	public $SxGeo;

	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array())
	{
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('gl_core_path', $config, $this->modx->getOption('core_path') . 'components/gl/');
		$assetsUrl = $this->modx->getOption('gl_assets_url', $config, $this->modx->getOption('assets_url') . 'components/gl/');
		$connectorUrl = $assetsUrl . 'connector.php';
		$assetsPath = MODX_ASSETS_PATH;

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,
			'actionUrl' => $assetsUrl . 'action.php',

			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/',
			'handlersPath' => $corePath . 'handlers/',
			'sypexgeoPath' => $assetsPath . 'components/gl/vendor/sypexgeo/',

			'prepareResponse' => true,
			'jsonResponse' => true,

		), $config);

		$this->modx->addPackage('gl', $this->config['modelPath']);
		$this->modx->lexicon->load('gl:default');
		$this->namespace = $this->getOption('namespace', $config, 'gl');

		$this->opts = &$_SESSION[$this->namespace]['opts'];
		if (empty($this->opts) OR !is_array($this->opts)) {
			$this->opts = array();
		}
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
	 * @param $key
	 * @param array $config
	 * @param null $default
	 * @return mixed|null
	 */
	public function getOption($key, $config = array(), $default = null)
	{
		$option = $default;
		if (!empty($key) AND is_string($key)) {
			if ($config != null AND array_key_exists($key, $config)) {
				$option = $config[$key];
			} elseif (array_key_exists($key, $this->config)) {
				$option = $this->config[$key];
			} elseif (array_key_exists("{$this->namespace}_{$key}", $this->modx->config)) {
				$option = $this->modx->getOption("{$this->namespace}_{$key}");
			}
		}
		return $option;
	}

	public function loadTools()
	{
		if (!is_object($this->Tools) OR !($this->Tools instanceof glSystemToolsInterface)) {
			$toolsClass = $this->modx->loadClass('tools.Tools', $this->config['handlersPath'], true, true);
			if ($derivedClass = $this->modx->getOption('gl_tools_handler_class', null, '', true)) {
				if ($derivedClass = $this->modx->loadClass('tools.' . $derivedClass, $this->config['handlersPath'], true, true)) {
					$toolsClass = $derivedClass;
				}
			}
			if ($toolsClass) {
				$this->Tools = new $toolsClass($this, $this->config);
			}
		}
		return !empty($this->Tools) AND $this->Tools instanceof glSystemToolsInterface;
	}

	public function loadSxGeo()
	{
		if (!is_object($this->SxGeo) OR !($this->SxGeo instanceof SxGeo)) {
			$sypexgeoClass = $this->modx->loadClass('sypexgeo.SxGeo', $this->config['handlersPath'], true, true);
			if ($derivedClass = $this->modx->getOption('gl_sypexgeo_handler_class', null, '', true)) {
				if ($derivedClass = $this->modx->loadClass('sypexgeo.' . $derivedClass, $this->config['handlersPath'], true, true)) {
					$sypexgeoClass = $derivedClass;
				}
			}
			if ($sypexgeoClass) {
				$this->SxGeo = new $sypexgeoClass($this->config['sypexgeoPath'] . 'data/SxGeoCity.dat');
			}
		}
		return !empty($this->SxGeo) AND $this->SxGeo instanceof SxGeo;
	}

	/**
	 * from https://github.com/bezumkin/pdoTools/blob/f947b2abd9511919de56cbb85682e5d0ef52ebf4/core/components/pdotools/model/pdotools/pdotools.class.php#L282
	 *
	 * Transform array to placeholders
	 *
	 * @param array $array
	 * @param string $plPrefix
	 * @param string $prefix
	 * @param string $suffix
	 * @param bool $uncacheable
	 * @return array
	 */
	public function makePlaceholders(array $array = array(), $plPrefix = '', $prefix = '[[+', $suffix = ']]', $uncacheable = true)
	{
		$result = array('pl' => array(), 'vl' => array());
		$uncached_prefix = str_replace('[[', '[[!', $prefix);
		foreach ($array as $k => $v) {
			if (is_array($v)) {
				$result = array_merge_recursive($result, $this->makePlaceholders($v, $plPrefix . $k . '.', $prefix, $suffix, $uncacheable));
			} else {
				$pl = $plPrefix . $k;
				$result['pl'][$pl] = $prefix . $pl . $suffix;
				$result['vl'][$pl] = $v;
				if ($uncacheable) {
					$result['pl']['!' . $pl] = $uncached_prefix . $pl . $suffix;
					$result['vl']['!' . $pl] = $v;
				}
			}
		}
		return $result;
	}

	/**
	 * Initializes component into different contexts.
	 *
	 * @param string $ctx The context to load. Defaults to web.
	 * @param array $scriptProperties
	 * @return boolean
	 */
	public function initialize($ctx = 'web', $scriptProperties = array())
	{
		$this->config = array_merge($this->config, $scriptProperties);
		$this->config['ctx'] = $ctx;

		if (!empty($this->initialized[$ctx])) {
			return true;
		}

		if (!$this->Tools) {
			$this->loadTools();
		}
		if (!$this->SxGeo) {
			$this->loadSxGeo();
		}
		$this->initialized[$ctx] = true;

		return true;
	}

	/**
	 * Independent registration of css and js
	 *
	 * @param string $objectName Name of object to initialize in javascript
	 */
	public function loadCustomJsCss($objectName = 'gl')
	{
		$config = $this->modx->toJSON(array(
			'assetsUrl' => $this->config['assetsUrl'],
			'actionUrl' => $this->config['actionUrl'],
		));

		$this->modx->regClientStartupScript(preg_replace('#(\n|\t)#', '', '
				<script type="text/javascript">
					glConfig=' . $config . '
				</script>
		'), true);

		if (!isset($this->modx->loadedjscripts[$objectName])) {

			$pls = $this->makePlaceholders($this->config);
			foreach ($this->config as $k => $v) {
				if (is_string($v)) {
					$this->config[$k] = str_replace($pls['pl'], $pls['vl'], $v);
				}
			}

			if ($this->config['jqueryJs']) {
				$this->modx->regClientScript(preg_replace('#(\n|\t)#', '', '
				<script type="text/javascript">
					if (typeof jQuery == "undefined") {
						document.write("<script src=\"' . $this->config['jqueryJs'] . '\" type=\"text/javascript\"><\/script>");
					}
				</script>
				'), true);
			}

			if ($this->config['colorboxJsCss']) {
				if ($this->config['colorboxCss']) {
					$this->modx->regClientCSS($this->config['colorboxCss']);
				}
				if ($this->config['colorboxJs']) {
					$this->modx->regClientScript($this->config['colorboxJs']);
				}
			}

			if ($this->config['frontendCss']) {
				$this->modx->regClientCSS($this->config['frontendCss']);
			}
			if ($this->config['frontendJs']) {
				$this->modx->regClientScript($this->config['frontendJs']);
			}

		}

		return $this->modx->loadedjscripts[$objectName] = 1;
	}


	/**
	 * return lexicon message if possibly
	 *
	 * @param $message
	 * @param array $placeholders
	 * @return string
	 */
	public function lexicon($message, $placeholders = array())
	{
		$key = '';
		if ($this->modx->lexicon->exists($message)) {
			$key = $message;
		} elseif ($this->modx->lexicon->exists($this->namespace . '_' . $message)) {
			$key = $this->namespace . '_' . $message;
		}
		if ($key !== '') {
			$message = $this->modx->lexicon->process($key, $placeholders);
		}
		return $message;
	}

	/**
	 * @return array
	 */
	public function getCountry()
	{
		return $this->SxGeo->getCountry($this->getUserIp());
	}

	/**
	 * @return integer
	 */
	public function getCountryId()
	{
		return $this->SxGeo->getCountryId($this->getUserIp());
	}

	/**
	 * @return array|bool false if city is not detected
	 */
	public function getCity()
	{
		return $this->SxGeo->getCity($this->getUserIp());
	}

	/**
	 * @return array
	 */
	public function getCityFull()
	{
		return $this->SxGeo->getCityFull($this->getUserIp());
	}

	/**
	 * @return string
	 */
	public static function getUserIp()
	{
		$ip = '127.0.0.1';

		switch (true) {
			case (isset($_SERVER['HTTP_CLIENT_IP']) AND $_SERVER['HTTP_CLIENT_IP'] != ''):
				$ip = $_SERVER['HTTP_CLIENT_IP'];
				break;
			case (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] != ''):
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				break;
			case (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != ''):
				$ip = $_SERVER['REMOTE_ADDR'];
				break;
		}

		return $ip;
	}

	/**
	 * @param string $message
	 * @param array $data
	 * @param array $placeholders
	 * @return array|string
	 */
	public function failure($message = '', $data = array(), $placeholders = array())
	{
		$response = array(
			'success' => false,
			'message' => $this->lexicon($message, $placeholders),
			'data' => $data,
		);
		return $this->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
	}

	/**
	 * @param string $message
	 * @param array $data
	 * @param array $placeholders
	 * @return array|string
	 */
	public function success($message = '', $data = array(), $placeholders = array())
	{
		$response = array(
			'success' => true,
			'message' => $this->lexicon($message, $placeholders),
			'data' => $data,
		);
		return $this->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
	}


}