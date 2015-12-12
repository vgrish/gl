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

			$level = $modx->getLogLevel();
			$modx->setLogLevel(xPDO::LOG_LEVEL_FATAL);

			$manager->addField('glData', 'resource', array('after' => 'class'));
			$manager->addField('glData', 'image', array('after' => 'address'));

			$modx->setLogLevel($level);


			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
