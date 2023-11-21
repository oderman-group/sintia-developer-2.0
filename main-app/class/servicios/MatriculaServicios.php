<?php
require_once("Servicios.php");
class MatriculaServicios 
{
    public static function listar($parametrosArray=null)
    {
      $sqlInicial="SELECT * FROM ".BD_ACADEMICA.".academico_matriculas";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('mat_grado','mat_grupo','mat_tipo_matricula','mat_acudiente','institucion','year');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal ="";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }
    
    public static function consultar($idDato = 1)
    {
      global $config;
        return Servicios::getSql("SELECT * FROM ".BD_ACADEMICA.".academico_matriculas WHERE mat_id='" . $idDato."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    }

    public static function listarEstudianteNombre($nombre='')
    {
      global $config;
      $sqlInicial= "SELECT mat_id,mat_primer_apellido,mat_segundo_apellido,mat_nombres,mat_nombre2 FROM ".BD_ACADEMICA.".academico_matriculas
      WHERE CONCAT(mat_primer_apellido,' ',mat_segundo_apellido,' ',mat_nombres,' ',mat_nombre2) LIKE '%".$nombre."%'"; 	  
      $sqlFinal ="AND mat_eliminado IN (0) AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql,'LIMIT 5');         
    }

    public static function apellidos($matricula){
        return strtoupper($matricula['mat_primer_apellido']." ".$matricula['mat_segundo_apellido']);
    }
    
    public static function nombres($matricula){
        return strtoupper($matricula['mat_nombres']." ".$matricula['mat_nombre2']);
    }

    public static function nombreCompleto($matricula){
        return MatriculaServicios::apellidos($matricula)." ".MatriculaServicios::nombres($matricula);
    }

}

