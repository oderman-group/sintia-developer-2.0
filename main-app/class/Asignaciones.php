<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Asignaciones {

    /**
    * Este metodo me trae todas las asignaciones de una evaluación
    * @param mysqli $conexion
    * @param array $config
    * @param array $idEvaluacion
    * 
    * @return mysqli_result $consulta
   **/
    public static function listarAsignaciones (
        mysqli $conexion, 
        array $config, 
        int $idEvaluacion
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT epag_id, uss1.uss_nombre, uss1.uss_nombre2, uss1.uss_apellido1, uss1.uss_apellido2, epag_id_evaluador, epag_estado, epag_tipo FROM ".BD_ADMIN.".general_evaluacion_asignar 
            INNER JOIN ".BD_GENERAL.".usuarios uss1 ON uss1.uss_id=epag_id_evaluado AND uss1.institucion = {$config['conf_id_institucion']} AND uss1.year = {$_SESSION["bd"]}
            WHERE epag_id_evaluacion='".$idEvaluacion."' AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me guarda una Asignación
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * 
     * @return string $codigo
    **/
    public static function guardarAsignaciones (
        mysqli $conexion, 
        array $config, 
        array $POST
    ) {

        foreach ($POST['evaluado'] as $idEvaluado){
        
            if($POST['evaluador'] == ACUDIENTE || $POST['evaluador'] == ESTUDIANTE || $POST['evaluador'] == DIRECTIVO || $POST['evaluador'] == DOCENTE){

                switch ($POST['evaluador']){
                    case DOCENTE:
                        $tipoUsuario = 2;
                    break;

                    case ACUDIENTE:
                        $tipoUsuario = 3;
                    break;

                    case ESTUDIANTE:
                        $tipoUsuario = 4;
                    break;

                    case DIRECTIVO:
                        $tipoUsuario = 5;
                    break;
                }

                $consulta = mysqli_query($conexion, "SELECT uss_id FROM ".BD_GENERAL.".usuarios WHERE uss_tipo='".$tipoUsuario."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                    try {
                        mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_evaluacion_asignar (epag_id_evaluacion, epag_id_evaluado, epag_id_evaluador, epag_tipo, epag_institucion, epag_year)VALUES('".$POST["idE"]."', '".$idEvaluado."', '".$resultado["uss_id"]."', '".$POST["tipoEncuesta"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                }
            }
        
            if($POST['evaluador'] == CURSO){
                $valoresIN = "'" . implode("','", $POST['evaluadorCursos']) . "'";

                $consulta = mysqli_query($conexion, "SELECT mat_id_usuario FROM ".BD_ACADEMICA.".academico_matriculas WHERE mat_grado IN ($valoresIN) AND mat_eliminado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
                while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {
                    try {
                        mysqli_query($conexion, "INSERT INTO ".BD_ADMIN.".general_evaluacion_asignar (epag_id_evaluacion, epag_id_evaluado, epag_id_evaluador, epag_tipo, epag_institucion, epag_year)VALUES('".$POST["idE"]."', '".$idEvaluado."', '".$resultado["mat_id_usuario"]."', '".$POST["tipoEncuesta"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
                    } catch (Exception $e) {
                        include("../compartido/error-catch-to-report.php");
                    }
                }
            }
        }
    }

    /**
     * Este metodo me trae la informacion de una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
     * 
     * @return array $resultado
    **/
    public static function traerDatosAsignaciones (
        mysqli $conexion, 
        array $config,
        string $idAsignacion
    )
    {
        $resultado = [];
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".general_evaluacion_asignar WHERE epag_id='{$idAsignacion}' AND epag_institucion = {$config['conf_id_institucion']} AND epag_year = {$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me actualiza una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
    **/
    public static function actualizarAsignaciones (
        mysqli $conexion, 
        array $config, 
        array $POST
    )
    {

        try {
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".general_evaluacion_asignar SET epag_id_evaluado='".$POST["evaluado"]."', epag_id_evaluador='".$POST["evaluador"]."', epag_tipo='".$POST["tipoEncuesta"]."' WHERE epag_id='".$POST["id"]."' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me elimina una asignación
     * @param mysqli $conexion
     * @param array $config
     * @param string $idAsignacion
    **/
    public static function eliminarAsignaciones (
        mysqli $conexion, 
        array $config, 
        string $idAsignacion
    )
    {

        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ADMIN.".general_evaluacion_asignar WHERE epag_id='{$idAsignacion}' AND epag_institucion={$config['conf_id_institucion']} AND epag_year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }
}