<?php

$properties = array();

$tmp = array(
	'tpl' => array(
		'type' => 'textfield',
		'value' => 'tpl.gl.modal'
	),

	'class' => array(
		'type' => 'list',
		'options' => array(
			array('text' => 'glCountry', 'value' => 'glCountry'),
			array('text' => 'glRegion', 'value' => 'glRegion'),
			array('text' => 'glCity', 'value' => 'glCity'),
		),
		'value' => 'glCity',
	),
	'modalShow' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),

	'jqueryJs' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]vendor/jquery/jquery.min.js',
	),

	'frontendCss' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]css/web/default.css',
	),
	'frontendJs' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]js/web/default.js',
	),

);

foreach ($tmp as $k => $v) {
	$properties[] = array_merge(
		array(
			'name' => $k,
			'desc' => PKG_NAME_LOWER . '_prop_' . $k,
			'lexicon' => PKG_NAME_LOWER . ':properties',
		), $v
	);
}

return $properties;