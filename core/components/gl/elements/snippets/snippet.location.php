<?php
/** @var array $scriptProperties */
/** @var gl $gl */
if (!$gl = $modx->getService('gl', 'gl', $modx->getOption('gl_core_path', null, $modx->getOption('core_path') . 'components/gl/') . 'model/gl/', $scriptProperties)) {
	return 'Could not load gl class!';
}

$gl->initialize($scriptProperties['context'], $scriptProperties);
$gl->loadCustomJsCss($scriptProperties['snippetName']);

