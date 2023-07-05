<?php
require_once("Servicios.php");
class MediaTecnicaServicios extends Servicios
{
    public static function listar($parametrosArray=null)
    {
      global $baseDatosServicios;
      $sqlInicial="SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('matcur_id_matricula','matcur_id_institucion','matcur_years');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal ="";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }

    public static function listarMaterias($parametrosArray=null)
    {
      global $baseDatosServicios;
      $sqlInicial="SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos 
      INNER JOIN academico_matriculas ON matcur_id_matricula=mat_id
      INNER JOIN academico_cargas ON car_curso=matcur_id_curso
      INNER JOIN academico_materias ON academico_materias.mat_id=car_materia
      INNER JOIN academico_grados ON gra_id=car_curso
	    INNER JOIN usuarios ON uss_id=car_docente
      ";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('matcur_id_matricula','matcur_id_curso','matcur_id_institucion','matcur_years');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal ="";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }
    
    public static function listarEstudiantes($parametrosArray=null,string $BD    = '')
    {
      global $baseDatosServicios;
      
      $sqlInicial="SELECT * FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos 
      LEFT JOIN ".$BD."academico_matriculas ON matcur_id_matricula=mat_id
			LEFT JOIN ".$BD."academico_grados ON gra_id=matcur_id_curso
			LEFT JOIN ".$BD."usuarios ON uss_id=mat_id_usuario
      LEFT JOIN ".$baseDatosServicios.".opciones_generales ON ogen_id=mat_genero			
      ";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('matcur_id_matricula','matcur_id_curso','matcur_id_institucion','matcur_years');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $andPersonalizado=$parametrosArray['and'];
      $sqlFinal ="ORDER BY mat_grado, mat_grupo, mat_primer_apellido, mat_segundo_apellido, mat_nombres";
      $limite=$parametrosArray["limite"];
      $esArreglo=$parametrosArray["arreglo"];
      $sql=$sqlInicial." ".$andPersonalizado." ".$sqlFinal;
      return Servicios::SelectSql($sql,$limite,$esArreglo);         
    }
    

    public static function editar($idMatricula,$cursosId,$config)
    {
        global $baseDatosServicios;
        Servicios::UpdateSql(
            "DELETE FROM ".$baseDatosServicios.".mediatecnica_matriculas_cursos 
            WHERE matcur_id_matricula =".$idMatricula."
            AND matcur_id_institucion =".(int)$config['conf_id_institucion']."
            AND matcur_years          =".(int)$config['conf_agno']."
            ");
        MediaTecnicaServicios::guardar($idMatricula,$cursosId,$config);
    }

    public static function guardar($idMatricula,$arregloCursos,$config)
    {
        global $baseDatosServicios;
        foreach ($arregloCursos as $clave => $curso) {
            Servicios::InsertSql(
                " INSERT INTO ".$baseDatosServicios.".mediatecnica_matriculas_cursos(
                 matcur_id_curso, 
                 matcur_id_matricula,
                 matcur_id_institucion,
                 matcur_years
                 )
                 VALUES
                 (
                  '".$curso."',
                  '".$idMatricula."',
                  '".$config['conf_id_institucion']."',
                  '".$config['conf_agno']."'
                 )"
             );
        }        
    }
}
