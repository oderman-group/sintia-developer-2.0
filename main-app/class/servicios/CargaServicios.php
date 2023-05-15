<?php
require_once("Servicios.php");
$Servicio = new Servicios;

class CargaServicios
{
    public static function cantidadCursos($idCurso = 1)
    {
        return Servicios::getSql("SELECT COUNT(*) AS cargas_curso FROM academico_cargas WHERE car_curso=" . $idCurso);
    }

    public static function listar($parametrosArray=null)
    {
        $sqlInicial = "SELECT * FROM academico_cargas 
        INNER JOIN academico_materias ON mat_id=car_materia
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
