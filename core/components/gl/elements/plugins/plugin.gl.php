<?php

switch ($modx->event->name) {

	case 'OnHandleRequest':
		if ($this->modx->context->key == 'mgr') {
			return '';
		}
		$corePath = $modx->getOption('gl_core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
		/** @var gl $gl */
		$gl = $modx->getService('gl', 'gl', $corePath . 'model/gl/', array('core_path' => $corePath));
		if (empty($gl->opts['check'])) {
			$gl->initialize($this->modx->context->key);
			$gl->opts['real'] = $gl->getRealData();
			if (!isset($gl->opts['current'])) {
				$gl->opts['current'] = $gl->getDefaultData();
			}
			$gl->opts['check'] = true;
		}
		$gl->setPlaceholders($gl->opts);

		break;
}



