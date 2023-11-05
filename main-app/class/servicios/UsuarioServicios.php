<?php
require_once("Servicios.php");
class UsuarioServicios 
{
    public static function listar($parametrosArray=null)
    {
      $sqlInicial="SELECT * FROM usuarios";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('uss_tipo','uss_nombre');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal =" ORDER BY gra_vocal";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }
    
    public static function consultar($idDato = 1)
    {
        return Servicios::getSql("SELECT * FROM usuarios WHERE uss_id=" . $idDato);
    }

    public static function listarUsuariosEstudiante($parametrosArray=null)
    {
      global $config;
      $sqlInicial="SELECT * FROM ".BD_GENERAL.".usuarios_por_estudiantes WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('upe_id_usuario');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal ="";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }


    public static function editar($Post)
    {
       Servicios::UpdateSql(
            ""
        );
    }

    public static function guardar($Post, $codigoCurso, $config)
    {
        $idRegistro = Servicios::InsertSql(
            ""
        );
        return $idRegistro;
    }

    public static function apellidos($usuario){
        return strtoupper($usuario['uss_apellido1']." ".$usuario['uss_apellido2']);
    }
    
    public static function nombres($usuario){
        return strtoupper($usuario['uss_nombre']." ".$usuario['uss_nombre2']);
    }

    public static function nombreCompleto($usuario){
        return UsuarioServicios::apellidos($usuario)." ".UsuarioServicios::nombres($usuario);
    }

}
