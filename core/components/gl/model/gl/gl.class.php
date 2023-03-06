<?php

/**
 * The base class for gl.
 */
class gl
{
    /* @var modX $modx */
    public $modx;
    /** @var string $namespace */
    public $namespace = 'gl';
    /* @var array The array of config */
    public $config = array();
    /** @var array $initialized */
    public $initialized = array();
    /** @var array $opts */
    public $opts = array();

    /** @var SxGeo $SxGeo */
    public $SxGeo;
    /** @var Format $Format */
    public $Format;

    /**
     * @param modX  $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->getOption('core_path', $config, $this->modx->getOption('core_path') . 'components/gl/');
        $assetsUrl = $this->getOption('assets_url', $config, $this->modx->getOption('assets_url') . 'components/gl/');
        $connectorUrl = $assetsUrl . 'connector.php';
        $assetsPath = MODX_ASSETS_PATH;

        $this->config = array_merge(array(
            'assetsUrl'    => $assetsUrl,
            'cssUrl'       => $assetsUrl . 'css/',
            'jsUrl'        => $assetsUrl . 'js/',
            'imagesUrl'    => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,
            'actionUrl'    => $assetsUrl . 'action.php',

            'corePath'       => $corePath,
            'modelPath'      => $corePath . 'model/',
            'chunksPath'     => $corePath . 'elements/chunks/',
            'templatesPath'  => $corePath . 'elements/templates/',
            'chunkSuffix'    => '.chunk.tpl',
            'snippetsPath'   => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
            'handlersPath'   => $corePath . 'handlers/',
            'sypexgeoPath'   => $assetsPath . 'components/gl/vendor/sypexgeo/',

            'prepareResponse' => true,
            'jsonResponse'    => true,

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
     * @param       $key
     * @param array $config
     * @param null  $default
     * @param bool  $skipEmpty
     *
     * @return mixed|null
     */
    public function getOption($key, $config = array(), $default = null, $skipEmpty = false)
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
        if ($skipEmpty AND empty($option)) {
            $option = $default;
        }

        return $option;
    }

    /**
     * @param       $n
     * @param array $p
     */
    public function __call($n, array$p)
    {
        echo __METHOD__ . ' says: ' . $n;
    }

    /**
     * Initializes component into different contexts.
     *
     * @param string $ctx The context to load. Defaults to web.
     * @param array  $scriptProperties
     *
     * @return boolean
     */
    public function initialize($ctx = 'web', $scriptProperties = array())
    {
        $this->config = array_merge($this->config, $scriptProperties);
        $this->config['ctx'] = $ctx;

        if (!empty($this->initialized[$ctx])) {
            return true;
        }

        if (!$this->SxGeo) {
            $this->loadSxGeo();
        }
        if (!$this->Format) {
            $this->loadFormat();
        }

        $this->initialized[$ctx] = true;

        return true;
    }

    /**
     * @return bool
     */
    public function loadSxGeo()
    {
        if (!is_object($this->SxGeo) OR !($this->SxGeo instanceof SxGeo)) {
            $sypexgeoClass = $this->modx->loadClass('sypexgeo.SxGeo', $this->config['handlersPath'], true, true);
            if ($derivedClass = $this->modx->getOption('gl_sypexgeo_handler_class', null, '', true)) {
                if ($derivedClass = $this->modx->loadClass('sypexgeo.' . $derivedClass, $this->config['handlersPath'],
                    true, true)
                ) {
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
     * @return bool
     */
    public function loadFormat()
    {
        if (!is_object($this->Format) OR !($this->Format instanceof FormatInterface)) {
            $formatClass = $this->modx->loadClass('format.Format', $this->config['handlersPath'], true, true);
            if ($derivedClass = $this->modx->getOption('gl_format_handler_class', null, '', true)) {
                if ($derivedClass = $this->modx->loadClass('format.' . $derivedClass, $this->config['handlersPath'],
                    true, true)
                ) {
                    $formatClass = $derivedClass;
                }
            }
            if ($formatClass) {
                $this->Format = new $formatClass($this->modx, $this->config);
            }
        }

        return !empty($this->Format) AND $this->Format instanceof FormatInterface;
    }

    /**
     * Independent registration of css and js
     *
     * @param string $objectName Name of object to initialize in javascript
     */
    public function loadCustomJsCss($objectName = 'gl')
    {
        $config = json_encode(array(
            'assetsUrl'     => $this->config['assetsUrl'],
            'actionUrl'     => $this->config['actionUrl'],
            'modalShow'     => $this->config['modalShow'],
            'pageReload'    => $this->config['pageReload'],
            'locationClass' => $this->config['class'],
        ), true);

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
            } else {
                $this->modx->regClientScript(preg_replace('#(\n|\t)#', '', '
				<script type="text/javascript">
					if (typeof jQuery == "undefined") {
						document.write("<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js\" type=\"text/javascript\"><\/script>");
					}
				</script>
				'), true);
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
     * from
     * https://github.com/bezumkin/pdoTools/blob/f947b2abd9511919de56cbb85682e5d0ef52ebf4/core/components/pdotools/model/pdotools/pdotools.class.php#L282
     *
     * Transform array to placeholders
     *
     * @param array  $array
     * @param string $plPrefix
     * @param string $prefix
     * @param string $suffix
     * @param bool   $uncacheable
     *
     * @return array
     */
    public function makePlaceholders(
        array $array = array(),
        $plPrefix = '',
        $prefix = '[[+',
        $suffix = ']]',
        $uncacheable = true
    ) {
        $result = array('pl' => array(), 'vl' => array());
        $uncached_prefix = str_replace('[[', '[[!', $prefix);
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result = array_merge_recursive($result,
                    $this->makePlaceholders($v, $plPrefix . $k . '.', $prefix, $suffix, $uncacheable));
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
     * @param array  $array
     * @param string $prefix
     *
     * @return array
     */
    public function flattenArray(array $array = array(), $prefix = '', $separator = '-')
    {
        $outArray = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $outArray = $outArray + $this->flattenArray($value, $prefix . $key . $separator);
            } else {
                $outArray[$prefix . $key] = $value;
            }
        }

        return $outArray;
    }

    /**
     * @param string $classKey
     *
     * @return bool|string
     */
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

    /** {@inheritDoc} */
    public function createDefault()
    {
        if (!$this->modx->getCount('glCountry', array('default' => 1))) {
            $data = $this->modx->newObject('glCountry', array(
                'id'      => 1,
                'default' => 1,
                'active'  => 1,

                'iso'       => 'DD',
                'continent' => 'DD',
                'name_ru'   => 'По умолчанию',
                'name_en'   => 'Default',
                'lat'       => '60',
                'lon'       => '100',
                'timezone'  => 'Europe/Moscow',
            ));
            $data->save();
        }

        if (!$this->modx->getCount('glRegion', array('default' => 1))) {
            $data = $this->modx->newObject('glRegion', array(
                'id'      => 1,
                'default' => 1,
                'active'  => 1,

                'iso'      => 'DD-DD',
                'country'  => 'DD',
                'name_ru'  => 'По умолчанию',
                'name_en'  => 'Default',
                'timezone' => 'Europe/Moscow',
                'okato'    => '',
            ));
            $data->save();
        }

        if (!$this->modx->getCount('glCity', array('default' => 1))) {
            $data = $this->modx->newObject('glCity', array(
                'id'      => 1,
                'default' => 1,
                'active'  => 1,

                'region_id' => '1',
                'name_ru'   => 'По умолчанию',
                'name_en'   => 'Default',
                'lat'       => '60',
                'lon'       => '100',
                'okato'     => '',
            ));
            $data->save();
        }

        if (!$this->modx->getCount('glData', array('default' => 1))) {
            $data = $this->modx->newObject('glData', array(
                'id'      => 1,
                'default' => 1,

                'identifier' => 1,
                'class'      => 'glCity',
                'phone'      => '8999999999',
                'email'      => 'email@mail.ru',
                'address'    => '',
            ));
            $data->save();
        }
    }

    /**
     * @return array
     */
    public function getCountry()
    {
        if (!$this->SxGeo) {
            $this->loadSxGeo();
        }

        return $this->SxGeo->getCountry($this->getUserIp());
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
                $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $ip = $ip[0];
                break;
            case (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != ''):
                $ip = $_SERVER['REMOTE_ADDR'];
                break;
        }

        return $ip;
    }

    /**
     * @return integer
     */
    public function getCountryId()
    {
        if (!$this->SxGeo) {
            $this->loadSxGeo();
        }

        return $this->SxGeo->getCountryId($this->getUserIp());
    }

    /**
     * @return array|bool false if city is not detected
     */
    public function getCity()
    {
        if (!$this->SxGeo) {
            $this->loadSxGeo();
        }

        return $this->SxGeo->getCity($this->getUserIp());
    }

    /**
     * @return array
     */
    public function getCityFull()
    {
        if (!$this->SxGeo) {
            $this->loadSxGeo();
        }

        return $this->SxGeo->getCityFull($this->getUserIp());
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function processData(array $data = array())
    {
        if (!$this->Format) {
            $this->loadFormat();
        }

        return $this->Format->processData($data);
    }

    public function getObjectData($class = 'glCity', array $query = array('default' => 1))
    {
        $data = array();

        $q = $this->modx->newQuery($class, $query);
        $q->select($this->modx->getSelectColumns($class, $class));
        if ($q->prepare() && $q->stmt->execute()) {
            $data = $q->stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $data;
    }


    /**
     * @return array
     */
    public function getDefaultData()
    {
        /* array cache $options */
        $options = array(
            'cache_key' => 'gl/default/data',
            'cacheTime' => 0,
        );
        if (!$data = $this->getCache($options)) {
            $data = array(
                'city'    => $this->getObjectData('glCity'),
                'region'  => $this->getObjectData('glRegion'),
                'country' => $this->getObjectData('glCountry'),
                'data'    => $this->getObjectData('glData'),
            );

            if ($this->modx->getOption('gl_isprocess_data', null, true, true)) {
                $data = $this->processData($data);
            }

            $this->setCache($data, $options);
        }

        return $data;
    }


    /**
     * @return array
     */
    public function getCurrentData($id = 0, $class = 'glCity')
    {
        /* array cache $options */
        $options = array(
            'cache_key' => 'gl/data/' . $class . '/' . $id,
            'cacheTime' => 0,
        );

        if (!$data = $this->getCache($options)) {
            $data = array(
                'data' => $this->getObjectData('glData', array('identifier' => $id, 'class' => $class))
            );
            if (empty($data['data']) || empty($data['data']['id']) || (isset($data['data'][0]) && empty($data['data'][0]))) {
                $data['data'] = $this->getObjectData('glData');
            }

            switch ($class) {
                case 'glCountry':
                    if ($country = $this->getObjectData('glCountry', array('id' => $id))) {
                        $data['country'] = $country;
                    }
                    break;
                case 'glRegion':
                    if ($region = $this->getObjectData('glRegion', array('id' => $id))) {
                        $data['region'] = $region;
                    }

                    if (
                        !empty($region)
                        AND
                        $countryId = $this->modx->getOption('country', $region)
                        AND
                        $country = $this->getObjectData('glCountry', array('iso' => $countryId))
                    ) {
                        $data['country'] = $country;
                    }

                    break;
                case 'glCity':
                    if ($city = $this->getObjectData('glCity', array('id' => $id))) {
                        $data['city'] = $city;
                    }

                    if (
                        !empty($city)
                        AND
                        $regionId = $this->modx->getOption('region_id', $city)
                        AND
                        $region = $this->getObjectData('glRegion', array('id' => $regionId))
                    ) {
                        $data['region'] = $region;
                    }

                    if (
                        !empty($region)
                        AND
                        $countryId = $this->modx->getOption('country', $region)
                        AND
                        $country = $this->getObjectData('glCountry', array('iso' => $countryId))
                    ) {
                        $data['country'] = $country;
                    }

                    break;
                default:
                    $data = array();
                    break;
            }

            if ($this->modx->getOption('gl_isprocess_data', null, true, true)) {
                $data = $this->processData($data);
            }

            $this->setCache($data, $options);
        }

        return $data;
    }

    /**
     * @return array
     */
    public function getRealData()
    {
        $cityFull = (array)$this->getCityFull();

        $realId = 0;
        $realClass = $this->getOption('default_real_class', null, 'glCity', true);

        switch ($realClass) {
            case 'glCountry':
                $realId = !isset($cityFull['country']) ?: $cityFull['country']['id'];
                break;
            case 'glRegion':
                $realId = !isset($cityFull['region']) ?: $cityFull['region']['id'];
                break;
            case 'glCity':
                $realId = !isset($cityFull['city']) ?: $cityFull['city']['id'];
                break;
        }
        $currentData = (array)$this->getCurrentData((int)$realId, $realClass);
        $defaultData = (array)$this->getDefaultData();

        if (empty($currentData['data']) AND !empty($defaultData['data'])) {
            $currentData['data'] = $defaultData['data'];
        }

        return array_merge($cityFull, $currentData);
    }

    /**
     * Returns data from cache
     *
     * @param mixed $options
     *
     * @return mixed
     */
    public function getCache($options = array())
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        $cached = '';
        if (!empty($cacheOptions) && !empty($cacheKey) && $this->modx->getCacheManager()) {
            $cached = $this->modx->cacheManager->get($cacheKey, $cacheOptions);
        }

        return $cached;
    }

    /**
     * Returns key for cache of specified options
     *
     * @var mixed $options
     *
     * @return bool|string
     */
    protected function getCacheKey($options = array())
    {
        if (empty($options)) {
            $options = $this->config;
        }
        if (!empty($options['cache_key'])) {
            return $options['cache_key'];
        }
        $key = !empty($this->modx->resource)
            ? $this->modx->resource->getCacheKey()
            : '';

        return $key . '/' . sha1(serialize($options));
    }

    /**
     * Returns array with options for cache
     *
     * @param $options
     *
     * @return array
     */
    protected function getCacheOptions($options = array())
    {
        if (empty($options)) {
            $options = $this->config;
        }
        $cacheOptions = array(
            xPDO::OPT_CACHE_KEY     => !empty($options['cache_key'])
                ? 'default'
                : (!empty($this->modx->resource)
                    ? $this->modx->getOption('cache_resource_key', null, 'resource')
                    : 'default'),
            xPDO::OPT_CACHE_HANDLER => !empty($options['cache_handler'])
                ? $options['cache_handler']
                : $this->modx->getOption('cache_resource_handler', null, 'xPDOFileCache'),
            xPDO::OPT_CACHE_EXPIRES => $options['cacheTime'] !== ''
                ? (integer)$options['cacheTime']
                : (integer)$this->modx->getOption('cache_resource_expires', null, 0),
        );

        return $cacheOptions;
    }

    /**
     * Sets data to cache
     *
     * @param mixed $data
     * @param mixed $options
     *
     * @return string $cacheKey
     */
    public function setCache($data = array(), $options = array())
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        if (!empty($cacheKey) && !empty($cacheOptions) && $this->modx->getCacheManager()) {
            $this->modx->cacheManager->set(
                $cacheKey,
                $data,
                $cacheOptions[xPDO::OPT_CACHE_EXPIRES],
                $cacheOptions
            );
        }

        return $cacheKey;
    }

    /**
     * @param        $subject
     * @param string $prefix
     * @param string $separator
     * @param bool   $restore
     *
     * @return array
     */
    public function setPlaceholders($subject, $prefix = 'gl', $separator = '.', $restore = false)
    {
        $keys = array();
        $restored = array();
        if (is_array($subject)) {
            foreach ($subject as $key => $value) {
                $rv = $this->modx->toPlaceholder($key, $value, $prefix, $separator, $restore);
                if (isset($rv['keys'])) {
                    foreach ($rv['keys'] as $rvKey) {
                        $keys[] = $rvKey;
                    }
                }
                if ($restore === true && isset($rv['restore'])) {
                    $restored = array_merge($restored, $rv['restore']);
                }
            }
        }
        $return = array('keys' => $keys);
        if ($restore === true) {
            $return['restore'] = $restored;
        }

        return $return;
    }

    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array  $data Data to be transmitted to the processor
     *
     * @return mixed The result of the processor
     */
    public function runProcessor($action = '', $data = array(), $json = true)
    {
        if (empty($action)) {
            return false;
        }
        if ($error = $this->modx->getService('error', 'error.modError')) {
            $error->reset();
        }
        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data,
            array('processors_path' => $this->config['processorsPath']));

        if (!$json) {
            $this->setJsonResponse(false);
        }
        $result = $this->config['prepareResponse'] ? $this->prepareResponse($response) : $response;
        $this->setJsonResponse();

        return $result;
    }

    /**
     * @param bool $json
     *
     * @return bool
     */
    public function setJsonResponse($json = true)
    {
        return ($this->config['jsonResponse'] = $json);
    }

    /**
     * This method returns prepared response
     *
     * @param mixed $response
     *
     * @return array|string $response
     */
    public function prepareResponse($response)
    {
        if ($response instanceof modProcessorResponse) {
            $output = $response->getResponse();
        } else {
            $message = $response;
            if (empty($message)) {
                $message = $this->lexicon('err_unknown');
            }
            $output = $this->failure($message);
        }
        if ($this->config['jsonResponse'] AND is_array($output)) {
            $output = $this->modx->toJSON($output);
        } elseif (!$this->config['jsonResponse'] AND !is_array($output)) {
            $output = $this->modx->fromJSON($output);
        }

        return $output;
    }

    /**
     * return lexicon message if possibly
     *
     * @param       $message
     * @param array $placeholders
     *
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
     * @param string $message
     * @param array  $data
     * @param array  $placeholders
     *
     * @return array|string
     */
    public function failure($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->lexicon($message, $placeholders),
            'data'    => $data,
        );

        return $this->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
    }

    /**
     * @param string $name
     * @param array  $properties
     *
     * @return mixed|string
     */
    public function getChunk($name = '', array $properties = array())
    {
        if (strpos($name, '@INLINE') !== false) {
            $content = str_replace('@INLINE', '', $name);
            /** @var modChunk $chunk */
            $chunk = $this->modx->newObject('modChunk', array('name' => 'inline-' . uniqid()));
            $chunk->setCacheable(false);

            return $chunk->process($properties, $content);
        }

        return $this->modx->getChunk($name, $properties);
    }

    /**
     * @param string $message
     * @param array  $data
     * @param array  $placeholders
     *
     * @return array|string
     */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->lexicon($message, $placeholders),
            'data'    => $data,
        );

        return $this->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
    }

    /**
     * @param        $array
     * @param string $delimiter
     *
     * @return array|string
     */
    public function cleanAndImplode($array, $delimiter = ',')
    {
        $array = array_map('trim', $array);       // Trim array's values
        $array = array_keys(array_flip($array));  // Remove duplicate fields
        $array = array_filter($array);            // Remove empty values from array
        $array = implode($delimiter, $array);

        return $array;
    }

    /** @return array Fields Grid Countries */
    public function getFieldsGridCountries()
    {
        $fields = $this->getOption('fields_grid_countries', null,
            'id,name_ru,name_alt,iso,continent,lat,lon,timezone', true);
        $fields .= ',id,iso,name_ru,active,properties,actions';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    /**
     * @param        $array
     * @param string $delimiter
     *
     * @return array
     */
    public function explodeAndClean($array, $delimiter = ',')
    {
        $array = explode($delimiter, $array);     // Explode fields to array
        $array = array_map('trim', $array);       // Trim array's values
        $array = array_keys(array_flip($array));  // Remove duplicate fields
        $array = array_filter($array);            // Remove empty values from array
        return $array;
    }

    /** @return array Fields Grid Regions */
    public function getFieldsGridRegions()
    {
        $fields = $this->getOption('fields_grid_regions', null,
            'id,name_ru,name_alt,iso,country,timezone,okato', true);
        $fields .= ',id,iso,name_ru,country,active,properties,actions';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    /** @return array Fields Grid Cities */
    public function getFieldsGridCities()
    {
        $fields = $this->getOption('fields_grid_cities', null,
            'id,name_ru,name_alt,region_id,okato', true);
        $fields .= ',id,name_ru,region_id,active,properties,actions';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    /** @return array Fields Grid Data */
    public function getFieldsGridData()
    {
        $fields = $this->getOption('fields_grid_data', null,
            'id,class,identifier,phone,email', true);
        $fields .= ',id,class,identifier,active,properties,default,actions';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    /** @return array Fields Grid Data */
    public function getFieldsWindowData()
    {
        $fields = $this->getOption('fields_window_data', null,
            'id,default,class,resource,identifier,image,phone,email,address,active,name_alt', true);

        /* name_alt,phone_add,email_add,add1,add2,add3 */
        $fields .= ',id,default,class,identifier,active';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

}