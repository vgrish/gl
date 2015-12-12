<?php
/** @var array $scriptProperties */
$context = $scriptProperties['context'] = $modx->getOption('context', $scriptProperties, $modx->context->key, true);
$class = $scriptProperties['class'] = $modx->getOption('class', $scriptProperties, 'glCity', true);
$objectName = $scriptProperties['objectName'] = $modx->getOption('objectName', $scriptProperties, 'gl', true);
$modalShow = $scriptProperties['modalShow'] = $modx->getOption('modalShow', $scriptProperties, true, true);

/** @var gl $gl */
if (!$gl = $modx->getService('gl', 'gl', $modx->getOption('gl_core_path', null, $modx->getOption('core_path') . 'components/gl/') . 'model/gl/', $scriptProperties)) {
	return 'Could not load gl class!';
}

if ($modalShow OR !empty($gl->opts['set'])) {
	$scriptProperties['modalShow'] = false;
}

$gl->initialize($context, $scriptProperties);
$gl->loadCustomJsCss($objectName);


$row = $scriptProperties;
$output = $gl->getChunk($tpl, $row);

if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
} else {
	return $output;
}