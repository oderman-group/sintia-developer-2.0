<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Scripts {

    /**
     * Este metodo me lista todas los scripts ejecutados en el sistema
     * @param mysqli $conexion
     * @param array $config
     * 
     * @return mysqli_result $consulta
    **/
    public static function listarScripts (
        mysqli $conexion,
        array $config
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".ejecucion_scripts 
            INNER JOIN ".BD_GENERAL.".usuarios ON uss_id=spt_responsable AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        return $consulta;
    }

    /**
     * Este metodo me trae los datos de un script
     * @param mysqli $conexion
     * @param array $config
     * @param int $idScript
     * 
     * @return array $resultado
    **/
    public static function datosScripts (
        mysqli $conexion,
        array $config,
        int $idScript
    )
    {
        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ADMIN.".ejecucion_scripts 
            INNER JOIN ".BD_GENERAL.".usuarios ON uss_id=spt_responsable AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            WHERE spt_id={$idScript}");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }
}