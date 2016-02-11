<?php

/** @var array $scriptProperties */
/** @var gl $gl */
$corePath = $modx->getOption('gl_core_path', null,
    $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/gl/');
$gl = $modx->getService('gl', 'gl', $corePath . 'model/gl/', array('core_path' => $corePath));

$className = 'gl' . $modx->event->name;
$modx->loadClass('glPlugin', $gl->getOption('modelPath') . 'gl/systems/', true, true);
$modx->loadClass($className, $gl->getOption('modelPath') . 'gl/systems/', true, true);
if (class_exists($className)) {
    /** @var $gl $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}
return;
