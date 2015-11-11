<?php

switch ($modx->event->name) {

	case 'OnWebPageInit':

		$corePath = $modx->getOption('gl_core_path', null, $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
		/** @var gl $gl */
		$gl = $modx->getService('gl', 'gl', $corePath . 'model/gl/', array('core_path' => $corePath));
		if (!$gl->SxGeo) {
			$gl->loadSxGeo();
		}


		$ip = $_SERVER['REMOTE_ADDR'];

		print_r($ip);
		print_r($gl->SxGeo->getCityFull($ip)); // Вся информация о городе
		print_r($gl->SxGeo->get($ip));


		break;

}



