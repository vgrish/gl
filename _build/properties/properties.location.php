<?php

$properties = array();

$tmp = array(

	'tpl' => array(
		'type' => 'textfield',
		'value' => 'tpl.gl.location'
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

	'limit' => array(
		'type' => 'numberfield',
		'value' => 10
	),
	'offset' => array(
		'type' => 'numberfield',
		'value' => 0
	),
	'where' => array(
		'type' => 'textfield',
		'value' => ''
	),
	'outputSeparator' => array(
		'type' => 'textfield',
		'value' => "\n"
	),
	'toPlaceholder' => array(
		'type' => 'textfield',
		'value' => ''
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