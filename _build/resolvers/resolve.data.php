<?php
/**
 * Resolve creating needed statuses
 *
 * @var xPDOObject $object
 * @var array $options
 */
if ($object->xpdo) {
	/* @var modX $modx */
	$modx =& $object->xpdo;
	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
		case xPDOTransport::ACTION_UPGRADE:
			$modelPath = $modx->getOption('gl_core_path', null, $modx->getOption('core_path') . 'components/gl/') . 'model/';
			$modx->addPackage('gl', $modelPath);

			/* glProfit */
			$datas = array(
				'1' => array(
					'identifier' => 1,
					'class' => '',
					'phone' => '89997733333',
					'email' => 'email@mail.ru',
					'address' => '',
				),
			);

			foreach ($datas as $id => $properties) {
				if (!$data = $modx->getCount('glData', array('id' => $id))) {
					$data = $modx->newObject('glData', array_merge(array(
						'default' => 1,
					), $properties));
					$data->set('id', $id);
					if ($data->save()) {

					}
				}
			}

			break;
		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;