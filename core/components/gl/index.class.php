<?php

/**
 * Class glMainController
 */
abstract class glMainController extends modExtraManagerController {
	/** @var gl $gl */
	public $gl;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('gl_core_path', null, $this->modx->getOption('core_path') . 'components/gl/');
		require_once $corePath . 'model/gl/gl.class.php';

		$this->gl = new gl($this->modx);
		$this->gl->initialize($this->modx->context->key);

		$this->addCss($this->gl->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->gl->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addCss($this->gl->config['assetsUrl'] . 'vendor/fontawesome/css/font-awesome.min.css');
		$this->addJavascript($this->gl->config['jsUrl'] . 'mgr/gl.js');

		$config = $this->gl->config;
		$config['connector_url'] = $this->gl->config['connectorUrl'];

		$this->addHtml("<script type='text/javascript'>gl.config={$this->modx->toJSON($config)}</script>");

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('gl:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends glMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'all';
	}
}