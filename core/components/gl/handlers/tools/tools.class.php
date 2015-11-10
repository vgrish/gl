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
	public function getRegions($opts = array());

	/** @inheritdoc} */
	public function getCities($opts = array());

	/** @inheritdoc} */
	public function hasResponseError();

	/** @inheritdoc} */
	public function hasResponseItems();

	/** @inheritdoc} */
	public function getResponseItems();

	/** @inheritdoc} */
	public function prepareResponse($response);


}

class Tools implements glSystemToolsInterface
{

	/** @var modX $modx */
	protected $modx;
	/** @var gl $gl */
	protected $gl;
	/** @var array $config */
	protected $config = array();

	/**
	 * @var array|string A reference to the full response
	 */
	protected $response = null;


	public function __construct($gl, $config)
	{
		$this->gl = &$gl;
		$this->modx = &$gl->modx;
		$this->config =& $config;

		if (!isset($this->config['vk_lang'])) {
			$this->config['vk_lang'] = '';
		}
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

	/** @inheritdoc} */
	public function getLink($params = array(), $type = 'getRegions')
	{
		$defaults = array();
		$defaults['v'] = $this->getOption('vk_version', null, '5.37');
		$defaults['country_id'] = $this->getOption('vk_country_id', null, 0);
		$defaults['offset'] = $this->getOption('vk_offset', null, 0);
		$defaults['count'] = $this->getOption('vk_count', null, 1000);
		$defaults['need_all'] = $this->getOption('vk_need_all', null, 1);

		switch ($type) {
			default:
			case 'getRegions':
				$fields = array('v', 'country_id', 'q', 'offset', 'count', 'need_all');
				break;
			case 'getCities':
				$fields = array('v', 'country_id', 'q', 'offset', 'count', 'need_all', 'region_id');
				break;
		}

		$opts = array();
		foreach ($fields as $field) {
			if (($params[$field] == '') AND !isset($defaults[$field])) {
				continue;
			} elseif (($params[$field] == '') AND isset($defaults[$field])) {
				$params[$field] = $defaults[$field];
			}
			$opts[] = implode('=', array($field, $params[$field]));
		}

		$link[] = $this->getOption('vk_url', null, 'http://api.vk.com/method/database.');
		$link[] = $type;
		$link[] = '?';
		$link[] = implode('&', $opts);

		return implode('', $link);
	}

	/** @inheritdoc} */
	public function getRegions($opts = array())
	{
		$response = $this->request($this->getLink($opts, 'getRegions'));

		return $this->prepareResponse($response);
	}

	/** @inheritdoc} */
	public function getCities($opts = array())
	{
		$response = $this->request($this->getLink($opts, 'getCities'));

		return $this->prepareResponse($response);
	}

	/** @inheritdoc} */
	public function hasResponseError()
	{
		return (empty($this->response) OR (isset($this->response['error']))) ? true : false;
	}

	/** @inheritdoc} */
	public function hasResponseItems()
	{
		return isset($this->response['response']['items']) AND !empty($this->response['response']['items']) ? true : false;
	}

	/** @inheritdoc} */
	public function getResponseItems()
	{
		return isset($this->response['response']['items']) ? $this->response['response']['items'] : array();
	}

	/** @inheritdoc} */
	public function prepareResponse($response)
	{
		$this->response = $this->modx->fromJSON($response);
		if (!is_array($this->response)) {
			$this->modx->log(modX::LOG_LEVEL_ERROR, "[gl] Service unavailable.\nResponse: $response");
		}

		return $this->response;
	}

	/** @inheritdoc} */
	function hostExists($url)
	{
		if (strpos($url, '/') === false) {
			$server = $url;
		} else {
			$server = @parse_url($url, PHP_URL_HOST);
		}
		if (!$server) {
			return false;
		}

		return !!gethostbynamel($server);
	}

	/** @inheritdoc} */
	protected function requestStreams($url, $method = 'GET', $params = array())
	{
		if (!$this->hostExists($url)) {
			throw new ErrorException("Could not connect to $url.", 404);
		}
		$opts = array();
		$params = http_build_query($params, '', '&');
		switch ($method) {
			case 'GET':
				$opts = array(
					'http' => array(
						'method' => "GET",
						'header' => "Accept-language: en\r\n" .
							"Cookie: remixlang={$this->config['vk_lang']}\r\n"
					),
				);
				$url = $url . ($params ? '?' . $params : '');
				break;
		}
		$context = stream_context_create($opts);
		$response = file_get_contents($url, false, $context);

		return $response;
	}

	/** @inheritdoc} */
	protected function requestCurl($url, $method = 'GET', $params = array())
	{
		$params = http_build_query($params, '', '&');
		$curl = curl_init($url . ($method == 'GET' && $params ? '?' . $params : ''));
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept-language: en\r\n" . "Cookie: remixlang={$this->config['vk_lang']}\r\n"));

		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		} else {
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_HTTPGET, true);
		}
		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			throw new ErrorException(curl_error($curl), curl_errno($curl));
		}
		curl_close($curl);

		return $response;
	}

	/** @inheritdoc} */
	protected function request($url, $method = 'GET', $params = array())
	{
		if (
			function_exists('curl_init') AND
			(
				!in_array('https', stream_get_wrappers()) OR
				!ini_get('safe_mode') AND
				!ini_get('open_basedir')
			)
		) {
			return $this->requestCurl($url, $method, $params);
		}

		return $this->requestStreams($url, $method, $params);
	}


}