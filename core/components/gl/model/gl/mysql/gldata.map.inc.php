<?php
$xpdo_meta_map['glData'] = array(
    'package'   => 'gl',
    'version'   => '1.1',
    'table'     => 'gl_data',
    'extends'   => 'xPDOSimpleObject',
    'fields'    =>
        array(
            'identifier' => 0,
            'class'      => 'glCity',
            'resource'   => 0,
            'phone'      => null,
            'email'      => null,
            'address'    => null,
            'image'      => null,
            'default'    => 0,
            'properties' => null,
        ),
    'fieldMeta' =>
        array(
            'identifier' =>
                array(
                    'dbtype'     => 'int',
                    'precision'  => '10',
                    'attributes' => 'unsigned',
                    'phptype'    => 'integer',
                    'null'       => false,
                    'default'    => 0,
                ),
            'class'      =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '100',
                    'phptype'   => 'string',
                    'null'      => false,
                    'default'   => 'glCity',
                ),
            'resource'   =>
                array(
                    'dbtype'     => 'int',
                    'precision'  => '10',
                    'attributes' => 'unsigned',
                    'phptype'    => 'integer',
                    'null'       => false,
                    'default'    => 0,
                ),
            'phone'      =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '20',
                    'phptype'   => 'string',
                    'null'      => true,
                ),
            'email'      =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '255',
                    'phptype'   => 'string',
                    'null'      => true,
                ),
            'address'    =>
                array(
                    'dbtype'  => 'text',
                    'phptype' => 'string',
                    'null'    => true,
                ),
            'image'      =>
                array(
                    'dbtype'    => 'varchar',
                    'precision' => '255',
                    'phptype'   => 'string',
                    'null'      => true,
                ),
            'default'    =>
                array(
                    'dbtype'    => 'tinyint',
                    'precision' => '1',
                    'phptype'   => 'boolean',
                    'null'      => true,
                    'default'   => 0,
                ),
            'properties' =>
                array(
                    'dbtype'  => 'text',
                    'phptype' => 'json',
                    'null'    => true,
                ),
        ),
    'indexes'   =>
        array(
            'unique_key' =>
                array(
                    'alias'   => 'unique_key',
                    'primary' => false,
                    'unique'  => true,
                    'type'    => 'BTREE',
                    'columns' =>
                        array(
                            'identifier' =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                            'class'      =>
                                array(
                                    'length'    => '',
                                    'collation' => 'A',
                                    'null'      => false,
                                ),
                        ),
                ),
            'default'    =>
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
        ),
);
