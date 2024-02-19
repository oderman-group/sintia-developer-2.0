<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Respuesta {

    /**
    * Este metodo me trae todos las respuestas
    * @param mysqli $conexion
    * @param array $config
    * 
    * @return mysqli_result $consulta
   **/
    public static function listarRespuestas (
        mysqli $conexion, 
        array $config
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_respuestas 
            WHERE resg_eliminado='".NO."' AND resg_institucion = {$config['conf_id_institucion']} AND resg_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
     * @return string $codigo
    **/
    public static function guardarRespuestas (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_respuestas (resg_descripcion, resg_valor, resg_institucion, resg_year)VALUES('".$POST["descripcion"]."', '".$POST["valor"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $idRegistro = mysqli_insert_id($conexion);

        return $idRegistro;
    }

    /**
     * Este metodo me trae la informacion de una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idRespuesta
     * 
     * @return array $resultado
    **/
    public static function traerDatosRespuestas (
        mysqli $conexion, 
        array $config,
        string $idRespuesta
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_respuestas WHERE resg_id='{$idRespuesta}' AND resg_institucion = {$config['conf_id_institucion']} AND resg_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me actualiza una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
    **/
    public static function actualizarRespuestas (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_respuestas SET resg_descripcion='".$POST["descripcion"]."', resg_valor='".$POST["valor"]."' WHERE resg_id='".$POST["id"]."' AND resg_institucion={$config['conf_id_institucion']} AND resg_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina una respuesta
     * @param mysqli $conexion
     * @param array $config
     * @param string $idRespuesta
    **/
    public static function eliminarRespuestas (
        mysqli $conexion, 
        array $config, 
        string $idRespuesta
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_respuestas SET resg_eliminado='".SI."' WHERE resg_id='{$idRespuesta}' AND resg_institucion={$config['conf_id_institucion']} AND resg_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
   * Valida si la respuesta ya esta asignada a una pregunta
   *
   * @param mysqli $conexion
   * @param int $idRespuesta
   *
   * @return int $num
   */
    public static function validarPreguntas(
        mysqli $conexion,
        int $idRespuesta
        ) {
        try{
            $consulta = mysqli_query($conexion, "SELECT gpr_id_pregunta FROM ".BD_ADMIN.".general_preguntas_respuestas WHERE gpr_id_respuesta='".$idRespuesta."'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $num = mysqli_num_rows($consulta);

        return $num;
    }
}