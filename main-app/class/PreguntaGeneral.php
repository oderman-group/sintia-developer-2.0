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
          $parametrosValidos = array('pregg_id_evaluacion','pregg_tipo_pregunta','pregg_obligatoria','evag_editada','pregg_id_evaluacion','pregg_year','pregg_visible','pregg_institucion');
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

}