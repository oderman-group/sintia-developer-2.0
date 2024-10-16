<?php
require_once 'BDT_tablas.php';

class BDT_Configuracion extends BDT_Tablas {

    public const CONFIG_SISTEMA_GENERAL        = 'CONFIG_SISTEMA_GENERAL';
    public const CONFIG_SISTEMA_COMPORTAMIENTO = 'CONFIG_SISTEMA_COMPORTAMIENTO';
    public const CONFIG_SISTEMA_PREFERENCIAS   = 'CONFIG_SISTEMA_PREFERENCIAS';
    public const CONFIG_SISTEMA_INFORMES       = 'CONFIG_SISTEMA_INFORMES';
    public const CONFIG_SISTEMA_PERMISOS       = 'CONFIG_SISTEMA_PERMISOS';
    public const CONFIG_SISTEMA_ESTILOS        = 'CONFIG_SISTEMA_ESTILOS';

    public static $tableName = 'configuracion';

}