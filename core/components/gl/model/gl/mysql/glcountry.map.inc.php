<?php
$xpdo_meta_map['glCountry'] = array(
    'package'   => 'gl',
    'version'   => '1.1',
    'table'     => 'gl_countries',
    'extends'   => 'xPDOSimpleObject',
    'fields'    =>
        array(
            'iso'       => '',
            'continent' => '',
            'name_ru'   => '',
            'name_en'   => '',
            'lat'       => 0,
            'lon'       => 0,
            'timezone'  => '',
            'default'   => 0,
            'active'    => 0,
        ),
    'fieldMeta' =>
        array(
            'iso'       =>
                array(
                    'dbtype'    => 'char',
                    'precision' => '2',
                    'phptype'   => 'string',
                    'null'      => false,
                    'default'   => '',
                ),
            'continent' =>
                array(
                    'dbtype'    => 'char',
                    'precision' => '2',
                    'phptype'   => 'string',
                    'null'      => false,
                    'default'   => '',
                ),
            'name_ru'   =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '128',
                    'phptype'   => 'varchar',
                    'null'      => false,
                    'default'   => '',
                ),
            'name_en'   =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '128',
                    'phptype'   => 'varchar',
                    'null'      => false,
                    'default'   => '',
                ),
            'lat'       =>
                array(
                    'dbtype'    => 'decimal',
                    'precision' => '6,2',
                    'phptype'   => 'float',
                    'null'      => false,
                    'default'   => 0,
                ),
            'lon'       =>
                array(
                    'dbtype'    => 'decimal',
                    'precision' => '6,2',
                    'phptype'   => 'float',
                    'null'      => false,
                    'default'   => 0,
                ),
            'timezone'  =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '30',
                    'phptype'   => 'varchar',
                    'null'      => false,
                    'default'   => '',
                ),
            'default'   =>
                array(
                    'dbtype'    => 'tinyint',
                    'precision' => '1',
                    'phptype'   => 'boolean',
                    'null'      => true,
                    'default'   => 0,
                ),
            'active'    =>
                array(
                    'dbtype'    => 'tinyint',
                    'precision' => '1',
                    'phptype'   => 'boolean',
                    'null'      => true,
                    'default'   => 0,
                ),
        ),
    'indexes'   =>
        array(
            'iso'       =>
                array(
                    'alias'   => 'iso',
                    'primary' => false,
                    'unique'  => false,
                    'type'    => 'BTREE',
                    'columns' =>
                        array(
                            'iso' =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                        ),
                ),
            'continent' =>
                array(
                    'alias'   => 'continent',
                    'primary' => false,
                    'unique'  => false,
                    'type'    => 'BTREE',
                    'columns' =>
                        array(
                            'continent' =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                        ),
                ),
            'timezone'  =>
                array(
                    'alias'   => 'timezone',
                    'primary' => false,
                    'unique'  => false,
                    'type'    => 'BTREE',
                    'columns' =>
                        array(
                            'timezone' =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                        ),
                ),
            'default'   =>
                array(
                    'alias'   => 'default',
                    'primary' => false,
                    'unique'  => false,
                    'type'    => 'BTREE',
                    'columns' =>
                        array(
                            'default' =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                        ),
                ),
            'active'    =>
                array(
                    'alias'   => 'active',
                    'primary' => false,
                    'unique'  => false,
                    'type'    => 'BTREE',
                    'columns' =>
                        array(
                            'active' =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                        ),
                ),
        ),
);
