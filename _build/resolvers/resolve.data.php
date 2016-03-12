<?php
/**
 * Resolve creating needed statuses
 *
 * @var xPDOObject $object
 * @var array      $options
 */
if ($object->xpdo) {
    /* @var modX $modx */
    $modx =& $object->xpdo;
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('gl_core_path', null,
                    $modx->getOption('core_path') . 'components/gl/') . 'model/';

            /** @var gl $gl */
            $gl = $modx->getService('gl', 'gl', $modelPath . 'gl/');
            $gl->initialize();

            $gl->createDefault();

            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;