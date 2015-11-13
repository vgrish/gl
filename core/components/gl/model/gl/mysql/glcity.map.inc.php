<?php
$xpdo_meta_map['glCity']= array (
  'package' => 'gl',
  'version' => '1.1',
  'table' => 'gl_cities',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'region_id' => 0,
    'name_ru' => '',
    'name_en' => '',
    'lat' => 0,
    'lon' => 0,
    'okato' => '',
    'default' => 0,
    'active' => 0,
  ),
  'fieldMeta' => 
  array (
    'region_id' => 
    array (
      'dbtype' => 'mediumint',
      'precision' => '8',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'name_ru' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'varchar',
      'null' => false,
      'default' => '',
    ),
    'name_en' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '128',
      'phptype' => 'varchar',
      'null' => false,
      'default' => '',
    ),
    'lat' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,5',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'lon' => 
    array (
      'dbtype' => 'decimal',
      'precision' => '10,5',
      'phptype' => 'float',
      'null' => false,
      'default' => 0,
    ),
    'okato' => 
    array (
      'dbtype' => 'char',
      'precision' => '20',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'default' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
  ),
  'indexes' => 
  array (
    'region_id' => 
    array (
      'alias' => 'region_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'region_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'okato' => 
    array (
      'alias' => 'okato',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'okato' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'default' => 
    array (
      'alias' => 'default',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'default' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'active' => 
    array (
      'alias' => 'active',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'active' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
);
