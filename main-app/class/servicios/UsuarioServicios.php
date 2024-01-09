<?php
require_once("Servicios.php");
class UsuarioServicios 
{
      /**
     * Lista usuarios según los parámetros proporcionados.
     *
     * @param array|null $parametrosArray Arreglo asociativo con los parámetros de filtrado.
     *
     * @return array Devuelve un conjunto de resultados de la consulta de usuarios.
     */
    public static function listar($parametrosArray=null)
    {
      $sqlInicial="SELECT * FROM ".BD_GENERAL.".usuarios";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('uss_tipo','uss_nombre','institucion','year');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal =" ORDER BY gra_vocal";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }
    /**
     * Consulta un usuario por su ID.
     *
     * @param int $idDato ID del usuario a consultar.
     *
     * @return array Devuelve un conjunto de resultados de la consulta del usuario.
     */
    public static function consultar($idDato = 1)
    {
        return Servicios::getSql("SELECT * FROM ".BD_GENERAL.".usuarios WHERE uss_id='" . $idDato."' AND institucion={$_SESSION["idInstitucion"]} AND year={$_SESSION["bd"]}");
    }

    /**
     * Lista usuarios asociados a estudiantes según los parámetros proporcionados.
     *
     * @param array|null $parametrosArray Arreglo asociativo con los parámetros de filtrado.
     *
     * @return array Devuelve un conjunto de resultados de la consulta de usuarios asociados a estudiantes.
     */
    public static function listarUsuariosEstudiante($parametrosArray=null)
    {
      global $config;
      $sqlInicial="SELECT * FROM ".BD_GENERAL.".usuarios_por_estudiantes";
      if($parametrosArray && count($parametrosArray)>0){
        $parametrosValidos=array('upe_id_usuario','institucion','year');
        $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
      };
      $sqlFinal ="";
      $sql=$sqlInicial.$sqlFinal;
      return Servicios::SelectSql($sql);         
    }

    /**
     * Edita un usuario con los datos proporcionados.
     *
     * @param array $Post Datos del usuario a editar.
     *
     * @return void
     */
    public static function editar($Post)
    {
       Servicios::UpdateSql(
            ""
        );
    }

    /**
     * Guarda un nuevo usuario con los datos proporcionados.
     *
     * @param array $Post         Datos del usuario a guardar.
     * @param string $codigoCurso Código del curso asociado al usuario.
     * @param array $config       Configuración de la aplicación.
     *
     * @return int ID del nuevo usuario guardado.
     */
    public static function guardar($Post, $codigoCurso, $config)
    {
        $idRegistro = Servicios::InsertSql(
            ""
        );
        return $idRegistro;
    }
    /**
     * Obtiene los apellidos del usuario.
     *
     * @param array $usuario Datos del usuario.
     *
     * @return string Apellidos del usuario en mayúsculas.
     */
    public static function apellidos($usuario){
        return strtoupper($usuario['uss_apellido1']." ".$usuario['uss_apellido2']);
    }
    
    /**
     * Obtiene los nombres del usuario.
     *
     * @param array $usuario Datos del usuario.
     *
     * @return string Nombres del usuario en mayúsculas.
     */
    public static function nombres($usuario){
        return strtoupper($usuario['uss_nombre']." ".$usuario['uss_nombre2']);
    }
    
    /**
     * Obtiene el nombre completo del usuario.
     *
     * @param array $usuario Datos del usuario.
     *
     * @return string Nombre completo del usuario en mayúsculas.
     */
    public static function nombreCompleto($usuario){
        return UsuarioServicios::apellidos($usuario)." ".UsuarioServicios::nombres($usuario);
    }

}
