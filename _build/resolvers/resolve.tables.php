<?php

if ($object->xpdo) {
    /** @var modX $modx */
    $modx =& $object->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modelPath = $modx->getOption('gl_core_path', null,
                    $modx->getOption('core_path') . 'components/gl/') . 'model/';
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

            $manager->addField('glData', 'name_alt', array('after' => 'class'));
            $manager->addField('glData', 'phone_add', array('after' => 'phone'));
            $manager->addField('glData', 'email_add', array('after' => 'email'));
            $manager->addField('glData', 'resource', array('after' => 'class'));
            $manager->addField('glData', 'image', array('after' => 'address'));
            $manager->addField('glData', 'add1', array('after' => 'properties'));
            $manager->addField('glData', 'add2', array('after' => 'add1'));
            $manager->addField('glData', 'add3', array('after' => 'add2'));

            $modx->setLogLevel($level);


            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}
return true;
