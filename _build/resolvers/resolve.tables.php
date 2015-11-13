<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
			$modelPath = $modx->getOption('gl_core_path', null, $modx->getOption('core_path') . 'components/gl/') . 'model/';
			$modx->addPackage('gl', $modelPath);

			$manager = $modx->getManager();
			$objects = array(
				'glCountry',
				'glRegion',
				'glCity',
				'glData'
			);
			foreach ($objects as $tmp) {
				$manager->createObjectContainer($tmp);
			}

			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
