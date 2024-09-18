<?php
require_once 'BDT_tablas.php';

class BDT_Aspirante extends BDT_Tablas {

    public const ESTADO_OCULTO_FALSO     = 0;
    public const ESTADO_OCULTO_VERDADERO = 1;

    public static $tableName = 'aspirantes';

}