<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Evaluaciones{
    /**
     * Este metodo me trae la cantidad de preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     * 
     * @return int $numPreguntas
     */
    public static function numeroPreguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        $numPreguntas=0;
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas aca_eva_pre
            INNER JOIN ".BD_ACADEMICA.".academico_actividad_preguntas preg ON preg.preg_id=aca_eva_pre.evp_id_pregunta AND preg.institucion={$config['conf_id_institucion']} AND preg.year={$_SESSION["bd"]}
            WHERE evp_id_evaluacion='".$idEvaluacion."' AND aca_eva_pre.institucion={$config['conf_id_institucion']} AND aca_eva_pre.year={$_SESSION["bd"]}
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $numPreguntas = mysqli_num_rows($consulta);

        return $numPreguntas;
    }
    
    /**
     * Este metodo me guarda la relación entre una pregunta y una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idPregunta
     * @param array $POST
     */
    public static function guardarRelacionPreguntaEvaluacion(mysqli $conexion, array $config, string $idPregunta, array $POST){
        $codigoEVP=Utilidades::generateCode("EVP");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas(evp_id, evp_id_evaluacion, evp_id_pregunta, institucion, year)VALUES('".$codigoEVP."', '".$POST["idE"]."','".$idPregunta."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo elimina toda las preguntas de una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idEvaluacion
     */
    public static function eliminarPreguntasEvaluacion(mysqli $conexion, array $config, string $idEvaluacion){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas WHERE evp_id_evaluacion='".$idEvaluacion."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina una pregunta en una evaluación
     * @param mysqli $conexion
     * @param array $config
     * @param array $GET
     */
    public static function eliminarUnaPreguntaEvaluacion(mysqli $conexion, array $config, array $GET){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluacion_preguntas 
            WHERE evp_id_evaluacion='".base64_decode($GET["idE"])."' AND evp_id_pregunta='".base64_decode($GET["idP"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
}