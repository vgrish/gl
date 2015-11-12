<?php

$properties = array();

$tmp = array(

	'tpl' => array(
		'type' => 'textfield',
		'value' => 'tpl.gl.chooser'
	),

//	'id' => array(
//		'type' => 'textfield',
//		'value' => ''
//	),
//	'chunk' => array(
//		'type' => 'textfield',
//		'value' => 'tpl.QuickView.msProduct',
//	),
//	'form' => array(
//		'type' => 'textfield',
//		'value' => 'form.QuickView.msProduct',
//	),


//
//	'actionUrl' => array(
//		'type' => 'textfield',
//		'value' => '[[+assetsUrl]]action.php',
//	),


	'objectName' => array(
		'type' => 'textfield',
		'value' => 'gl',
	),
	'colorboxJsCss' => array(
		'type' => 'combo-boolean',
		'value' => true,
	),
	'colorboxJs' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]vendor/colorbox/jquery.colorbox.js',
	),
	'colorboxCss' => array(
		'type' => 'textfield',
		'value' => '[[+assetsUrl]]vendor/colorbox/colorbox.css',
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