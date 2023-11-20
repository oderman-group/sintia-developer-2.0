<?php
require_once("Servicios.php");
class CargaServicios
{
    public static function cantidadCursos($idCurso = 1)
    {
        return Servicios::getSql("SELECT COUNT(*) AS cargas_curso FROM academico_cargas WHERE car_curso=" . $idCurso);
    }

    public static function listar($parametrosArray=null)
    {
        global $config;

        $sqlInicial = "SELECT * FROM academico_cargas 
        INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
        INNER JOIN academico_grados ON gra_id=car_curso
        INNER JOIN usuarios ON uss_id=car_docente";
         if($parametrosArray && count($parametrosArray)>0){
            $parametrosValidos=array('car_curso','car_grupo');
            $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
          };
        $sqlFinal = " ";
        $sql = $sqlInicial . $sqlFinal;
        return Servicios::SelectSql($sql);  
    }
}
