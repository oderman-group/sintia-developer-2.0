<?php
require_once 'BDT_Join.php';
require_once 'BDT_tablas.php';
require_once 'BDT_JoinImplements.php';

class BDT_vista_datos_boletin extends BDT_Tablas implements BDT_JoinImplements
{

    public static $schema = BD_ACADEMICA;
    public static $tableName = 'vista_datos_boletin';
    public static $tableAs = 'dbol';

    use BDT_Join;

}
