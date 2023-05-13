<?php
require_once("Servicios.php");
$Servicio = new Servicios;

class MediaTecnicaServicios
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

    public static function guardar($idMatricula,$cursosId,$config)
    {
        global $baseDatosServicios;
        foreach ($cursosId as $clave => $curso) {
            $idRegistro = Servicios::InsertSql(
                " INSERT INTO ".$baseDatosServicios.".mediatecnica_matriculas_cursos(
                 matcur_id_curso, 
                 matcur_id_matricula,
                 matcur_id_institucion,
                 matcur_years
                 )
                 VALUES
                 (
                 ".$curso.",
                 ".$idMatricula.",
                 ".(int)$config['conf_id_institucion'].",
                 ".(int)$config['conf_agno']."
                 )"
             );
        }        
    }
}
