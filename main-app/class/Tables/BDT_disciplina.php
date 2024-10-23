<?php
require_once 'BDT_tablas.php';

class BDT_disciplina extends BDT_Tablas
{
    public static $tableName        = 'disiplina_nota';
    public static $tableMatricula   = 'academico_matriculas';
    public static $tableCargas      = 'academico_cargas';
    public static $tableUsuarios    = 'usuarios';
    public static $tableGrados      = 'academico_grados';
    public static $tableGrupos      = 'academico_grupos';
    public static $tableMaterias    = 'academico_materias';
}
