<?php

$settings = array();

$tmp = array(

	//временные

//	'assets_path' => array(
//		'value' => '{base_path}gl/assets/components/gl/',
//		'xtype' => 'textfield',
//		'area' => 'gl_temp',
//	),
//	'assets_url' => array(
//		'value' => '/gl/assets/components/gl/',
//		'xtype' => 'textfield',
//		'area' => 'gl_temp',
//	),
//	'core_path' => array(
//		'value' => '{base_path}gl/core/components/gl/',
//		'xtype' => 'textfield',
//		'area' => 'gl_temp',
//	),

	//временные

	/*
		'some_setting' => array(
			'xtype' => 'combo-boolean',
			'value' => true,
			'area' => 'gl_main',
		),
		*/
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'gl_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
