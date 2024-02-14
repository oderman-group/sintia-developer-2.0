<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once("servicios/Servicios.php");
class PreguntaGeneral  extends Servicios{

/**
     * Guarda la información de las Evaluciones
     *
     * @param array $Post Arreglo con los datos recibidos del post.
     *
     * @return array|false|string
     */
    public static function guardar($Post)
    {
      global  $config;
        $sql = 
        " INSERT INTO ".BD_ADMIN.".general_preguntas(
         pregg_descripcion, 
         pregg_tipo_pregunta,
         pregg_obligatoria,
         pregg_visible,
         pregg_institucion,
         pregg_year
         )
         VALUES
         (
          '".$Post["descripcion"]."',
          '".$Post["tipo_pregunta"]."',
          '".$Post["obligatoria"]."',
          '".$Post["visible"]."',
          '".$config['conf_id_institucion']."',
          '".$config['conf_agno']."'
         )        
        ";
        $idRegistro = Servicios::InsertSql($sql);
        return $idRegistro;
                  
    }

  /**
     * Edita la información de la Evaluacion
     *
     * @param array $Post Datos del formulario.
     *
     * @return void
     */
    public static function actualizar($Post)
    {
        Servicios::UpdateSql(
            "UPDATE " . BD_ADMIN . ".general_preguntas SET 
            pregg_descripcion = '" . $Post["descripcion"] . "', 
            pregg_tipo_pregunta = '" . $Post["tipo_pregunta"] . "', 
            pregg_obligatoria = '" . $Post["obligatoria"] . "',
            pregg_visible = '" . $Post["visible"] . "' 
            WHERE pregg_id = '" . $Post["id"] . "' "
        );
    }

      /**
     * Elimina la existencia de una Evaluacion
     * @param string $id ID del la evaluacion.
     *
     * @return void
     */
    public static function eliminar($id)
    {        
            Servicios::UpdateSql( "DELETE FROM " . BD_ADMIN . ".general_preguntas WHERE pregg_id = '".$id."';");         
    }

        /**
     * Consulta la información de una Evaluacion
     *
     * @param int $idDato Identificador de la Evaluacion.
     *
     * @return array|false Arreglo con la información de la Evaluacion o false si hay un error.
     */
    public static function consultar($idDato = 1)
    {
       
        return Servicios::getSql("SELECT * FROM " . BD_ADMIN . ".general_preguntas WHERE pregg_id = '" . $idDato."' ");
    }
      /**
     * Lista las Evaluaciones Registradas
     *
     * @param array|null $parametrosArray Arreglo de parámetros para filtrar la consulta (opcional).
     * @param string $yearBd Año de la base de datos (opcional).
     *
     * @return array|mysqli_result|false Arreglo de datos del resultado, objeto mysqli_result o false si hay un error.
     */
    public static function listar(
        $parametrosArray = null
      )
      {
        
        $sqlInicial = "SELECT * FROM ".BD_ADMIN.".general_preguntas";
        if($parametrosArray && count($parametrosArray)>0){
          $parametrosValidos = array('pregg_id_evaluacion','pregg_tipo_pregunta','pregg_obligatoria','evag_editada','pregg_id_evaluacion','pregg_year','pregg_visible');
          $sqlInicial = Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
        };
        $limite = !empty($parametrosArray['limite']) ? $parametrosArray['limite'] : "";
        $esArreglo = !empty($parametrosArray['arreglo']) ? $parametrosArray['arreglo'] : "";
        return Servicios::SelectSql($sqlInicial,$limite,$esArreglo);         
      }

      /**
     * Lista las repuestas de una pregunta
     *
     * @param mysqli $conexion
     * @param array $config
     * @param int $idPregunta
     *
     * @return mysqli_result $consulta
     */
    public static function traerRespuestasPreguntas(
        mysqli $conexion,
        array $config,
        int $idPregunta
      ) {
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_preguntas_respuestas 
            INNER JOIN ".BD_ADMIN.".general_respuestas ON resg_id=gpr_id_respuesta AND resg_institucion={$config['conf_id_institucion']} AND resg_year={$_SESSION["bd"]}
            WHERE gpr_id_pregunta='".$idPregunta."'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
      }

    /**
     * Lista la repuesta guardada de una pregunta
     *
     * @param mysqli $conexion
     * @param array $config
     * @param int $idPregunta
     * @param int $idAsignacion
     * @param string $idUsuario
     *
     * @return array $resultado
     */
    public static function existeRespuestaPregunta(
        mysqli $conexion,
        array $config,
        int $idPregunta,
        int $idAsignacion,
        string $idUsuario
    ) {
        $resultado = [];
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_resultados WHERE resg_id_pregunta='".$idPregunta."' AND resg_id_asignacion='".$idAsignacion."' AND resg_id_usuario='".$idUsuario."' AND resg_institucion={$config['conf_id_institucion']} AND resg_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        if (mysqli_num_rows($consulta) > 0){
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        }
        return $resultado;
    }

    /**
     * guarda la repuesta de una pregunta
     *
     * @param mysqli $conexion
     * @param array $config
     * @param int $idPregunta
     * @param int $idAsignacion
     * @param string $idUsuario
     * @param string $respuesta
     *
     * @return array $resultado
     */
    public static function guardarRespuestaPregunta(
        mysqli $conexion,
        array $config,
        int $idPregunta,
        int $idAsignacion,
        string $idUsuario,
        string $respuesta
    ) {
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_resultados(resg_id_pregunta, resg_respuesta, resg_id_usuario, resg_id_asignacion, resg_institucion, resg_year) VALUE ('".$idPregunta."', '".$respuesta."', '".$idUsuario."', '".$idAsignacion."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Actualiza la repuesta de una pregunta
     *
     * @param mysqli $conexion
     * @param array $config
     * @param int $idPregunta
     * @param int $idAsignacion
     * @param string $idUsuario
     * @param string $respuesta
     */
    public static function actualizarRespuestaPregunta(
        mysqli $conexion,
        array $config,
        int $idPregunta,
        int $idAsignacion,
        string $idUsuario,
        string $respuesta
    ) {
        try{
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_resultados SET resg_respuesta='".$respuesta."', resg_actualizaciones=resg_actualizaciones+1 WHERE resg_id_pregunta='".$idPregunta."' AND resg_id_asignacion='".$idAsignacion."' AND resg_id_usuario='".$idUsuario."' AND resg_institucion={$config['conf_id_institucion']} AND resg_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Para verificar si se respondieron todas las preguntas
     *
     * @param mysqli $conexion
     * @param array $config
     * @param int $idAsignacion
     * @param string $idUsuario
     *
     * @return int $num
     */
    public static function terminoEncuesta(
        mysqli $conexion,
        array $config,
        int $idAsignacion,
        string $idUsuario
    ) {
        try{
            $consulta = mysqli_query($conexion, "SELECT resg_respuesta FROM ".BD_ADMIN.".general_resultados 
            INNER JOIN ".BD_ADMIN.".general_preguntas ON pregg_id=resg_id_pregunta AND pregg_institucion={$config['conf_id_institucion']} AND pregg_year={$_SESSION["bd"]}
            WHERE resg_id_asignacion='".$idAsignacion."' AND resg_id_usuario='".$idUsuario."' AND pregg_obligatoria=1 AND pregg_visible=1 AND resg_institucion={$config['conf_id_institucion']} AND resg_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        
        $num = mysqli_num_rows($consulta);
        
        return $num;
    }

}