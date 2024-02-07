<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once("servicios/Servicios.php");
class EvaluacionGeneral  extends Servicios{

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
       
        $sql=
        " INSERT INTO ".BD_ADMIN.".general_evaluaciones(
         evag_nombre, 
         evag_descripcion,
         evag_clave,
         evag_fecha,
         evag_creada,
         evag_institucion,
         evag_year,
         evag_visible,
         evag_obligatoria
         )
         VALUES
         (
          '".$Post["nombre"]."',
          '".$Post["descripcion"]."',
          '".$Post["clave"]."',
          '".$Post["fecha"]."',
          '1',
          '".$config['conf_id_institucion']."',
          '".$config['conf_agno']."',
          '".$Post["visible"]."',
          '".$Post["obligatoria"]."'
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
            "UPDATE " . BD_ADMIN . ".general_evaluaciones SET 
            evag_fecha='" . $Post["fecha"] . "', 
            evag_nombre='" . $Post["nombre"] . "', 
            evag_descripcion='" . $Post["descripcion"] . "', 
            evag_clave='" . $Post["clave"] . "', 
            evag_editada=1, 
            evag_visible='" . $Post["visible"] . "', 
            evag_obligatoria='" . $Post["obligatoria"] . "'
            WHERE evag_id='" . $Post["id"] . "' "
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
            Servicios::UpdateSql( "DELETE FROM " . BD_ADMIN . ".general_evaluaciones WHERE evag_id='".$id."';");         
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
       
        return Servicios::getSql("SELECT * FROM " . BD_ADMIN . ".general_evaluaciones WHERE evag_id='" . $idDato."' ");
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
        $parametrosArray=null
      )
      {
        
        $sqlInicial="SELECT *, (SELECT COUNT(gep_id_evaluacion) as preguntas FROM ".BD_ADMIN.".general_evaluaciones_preguntas WHERE gep_id_evaluacion=evag_id) as preguntas FROM ".BD_ADMIN.".general_evaluaciones";
        if($parametrosArray && count($parametrosArray)>0){
          $parametrosValidos=array('evag_descripcion','evag_clave','evag_fecha','evag_editada','evag_institucion','evag_year','evag_visible');
          $sqlInicial=Servicios::concatenarWhereAnd($sqlInicial,$parametrosValidos,$parametrosArray);
        };
        $limite= !empty($parametrosArray['limite']) ? $parametrosArray['limite'] : "";
        $esArreglo= !empty($parametrosArray['arreglo']) ? $parametrosArray['arreglo'] : "";
        return Servicios::SelectSql($sqlInicial,$limite,$esArreglo);         
      }

}