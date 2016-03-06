<?php
$xpdo_meta_map['glRegion']= array (
  'package' => 'gl',
  'version' => '1.1',
  'table' => 'gl_regions',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'iso' => '',
    'country' => '',
    'name_ru' => '',
    'name_en' => '',
    'timezone' => '',
    'okato' => '',
    'default' => 0,
    'active' => 0,
  ),
  'fieldMeta' => 
  array (
    'iso' => 
    array (
      'dbtype' => 'char',
      'precision' => '7',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'country' => 
    array (
      'dbtype' => 'char',
      'precision' => '2',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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
    'timezone' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '30',
      'phptype' => 'varchar',
      'null' => false,
      'default' => '',
    ),
    'okato' => 
    array (
      'dbtype' => 'char',
      'precision' => '4',
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
    'iso' => 
    array (
      'alias' => 'iso',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'iso' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'country' => 
    array (
      'alias' => 'country',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'country' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'timezone' => 
    array (
      'alias' => 'timezone',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'timezone' => 
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
