<?php
require_once 'BDT_Join.php';
require_once 'BDT_tablas.php';
require_once 'BDT_JoinImplements.php';

class BDT_usuarios extends BDT_Tablas implements BDT_JoinImplements
{

    public static $schema = BD_GENERAL;
    public static $tableName = 'usuarios';
    public static $tableAs = 'uss';

    use BDT_Join;


}
