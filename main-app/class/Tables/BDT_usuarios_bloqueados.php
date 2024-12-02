<?php
require_once 'BDT_tablas.php';

class BDT_usuariosBloqueados extends BDT_Tablas {

    public const USUARIO_INDIVIDUAL = "USUARIO_INDIVIDUAL";
    public const USUARIO_GRUPAL     = "USUARIO_GRUPAL";
    public const MATRICULA          = "MATRICULA";

    public static $tableName = 'usuarios_bloqueados';

}