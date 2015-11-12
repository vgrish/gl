<?php

switch ($modx->event->name) {

	case 'OnWebPageInit':

		$corePath = $modx->getOption('gl_core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
		/** @var gl $gl */
		$gl = $modx->getService('gl', 'gl', $corePath . 'model/gl/', array('core_path' => $corePath));
		if (!empty($gl->opts['check'])) {
			return '';
		}
		$gl->initialize($this->modx->context->key);
		$gl->opts['location'] = $gl->getCityFull();
		$gl->opts['check'] = true;

		break;

	case 'OnHandleRequest':
		if ($this->modx->context->key == 'mgr') {
			return '';
		}
		$corePath = $modx->getOption('gl_core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
		/** @var gl $gl */
		$gl = $modx->getService('gl', 'gl', $corePath . 'model/gl/', array('core_path' => $corePath));
		$modx->setPlaceholders($gl->opts, 'gl.');

		break;
}



