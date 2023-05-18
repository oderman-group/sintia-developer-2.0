<?php
require_once("Servicios.php");
class MatriculaServicios 
{
    public static function listar($parametrosArray=null)
    {
      $sqlInicial="SELECT * FROM academico_matriculas";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('mat_grado','mat_grupo','mat_tipo_matricula','mat_acudiente');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal ="";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }
    
    public static function consultar($idDato = 1)
    {
        return Servicios::getSql("SELECT * FROM academico_matriculas WHERE mat_id=" . $idDato);
    }

    public static function listarEstudianteNombre($nombre='')
    {
      global $baseDatosServicios;
      // $sqlInicial="SELECT * 
      // FROM academico_matriculas
      // LEFT JOIN usuarios ON uss_id=mat_id_usuario
      // LEFT JOIN academico_grados ON gra_id=mat_grado
      // LEFT JOIN academico_grupos ON gru_id=mat_grupo
      // LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero
      // LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=mat_lugar_nacimiento
      // WHERE CONCAT(mat_primer_apellido,' ',mat_segundo_apellido,' ',mat_nombres) LIKE '%".$nombre."%'";  
      $sqlInicial= "SELECT mat_primer_apellido,mat_segundo_apellido,mat_nombres,mat_nombre2 FROM academico_matriculas
      WHERE CONCAT(mat_primer_apellido,' ',mat_segundo_apellido,' ',mat_nombres,' ',mat_nombre2) LIKE '%".$nombre."%'"; 	  
      $sqlFinal =" ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres";
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

