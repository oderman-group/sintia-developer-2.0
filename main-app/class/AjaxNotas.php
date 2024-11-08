<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/BindSQL.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");
class AjaxNotas extends Calificaciones {

    /**
     * Este metodo sirve para registrar la nivelación desde resumen por periodo de un estudiante
     * 
     * @param int $codEstudiante 
     * @param int $carga 
     * @param double $nota
     * 
     * @return array // se retorna mensaje de confirmación
    **/
    public static function ajaxNivelacionesRegistrar($data)
    {
        global $conexion, $config;

        try {
            $consulta = mysqli_query($conexion, "
            SELECT * FROM ".BD_ACADEMICA.".academico_nivelaciones 
            WHERE 
                niv_cod_estudiante='".$data['codEst']."'
            AND niv_id_asg='".$data['carga']."' 
            AND institucion={$config['conf_id_institucion']} 
            AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        $resultadoNivelaciones = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        if (empty($resultadoNivelaciones['niv_id'])) {
            try {
                $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_nivelaciones');

                mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_nivelaciones(niv_id, niv_id_asg, niv_cod_estudiante, niv_definitiva, niv_fecha, institucion, year)VALUES('".$codigo."', '".$data['carga']."','".$data['codEst']."','".$data['nota']."',now(), {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        } else {
            try {
                mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_nivelaciones SET niv_definitiva='".$data['nota']."', niv_fecha=now() 
                WHERE niv_id='".$resultadoNivelaciones['niv_id']."' 
                AND institucion={$config['conf_id_institucion']} 
                AND year={$_SESSION["bd"]}");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }

        $datosMensaje = [
            "heading" => "Cambios guardados",
            "estado"  => "success",
            'success' => 'true',
            "mensaje" => "Los cambios se ha guardado correctamente!."
        ];

        return $datosMensaje;
    }
}