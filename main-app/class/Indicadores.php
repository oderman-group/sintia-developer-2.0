<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Indicadores {

    /**
     * Este metodo me consulta la suma de los indicadores de la carga actual
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
     * 
     * @return array    $resultado
    **/
    public static function consultarSumaIndicadores (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        int     $periodo
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT
            (SELECT sum(ipc_valor) FROM ".BD_ACADEMICA.".academico_indicadores_carga 
            WHERE ipc_carga='".$idcarga."' AND ipc_periodo='".$periodo."' AND ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
            (SELECT sum(ipc_valor) FROM ".BD_ACADEMICA.".academico_indicadores_carga 
            WHERE ipc_carga='".$idcarga."' AND ipc_periodo='".$periodo."' AND ipc_creado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}),
            (SELECT count(*) FROM ".BD_ACADEMICA.".academico_indicadores_carga 
            WHERE ipc_carga='".$idcarga."' AND ipc_periodo='".$periodo."' AND ipc_creado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo guarda la relación de un indicador con su carga
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param string    $idIndicador
     * @param int       $periodo
     * @param array     $POST
     * @param array     $indicadorCopiado
    **/
    public static function guardarIndicadorCarga (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        string  $idIndicador,  
        int     $periodo,  
        array   $POST               = NULL,  
        array   $indicadorCopiado   = NULL
    )
    {
        $codigo             = Utilidades::generateCode("IPC");
        $copiado            = NULL;
        $evaluacion         = NULL;
        $valorIndicador     = NULL;

        if($POST != NULL){
            $evaluacion     = !empty($POST["saberes"]) ? $POST["saberes"] : NULL;
            $valorIndicador = !empty($POST["valor"]) ? $POST["valor"] : NULL;
        }
        
        if($indicadorCopiado != NULL){
            $evaluacion         = !empty($indicadorCopiado['ipc_evaluacion']) ? $indicadorCopiado['ipc_evaluacion'] : NULL;
            $copiado            = !empty($indicadorCopiado['ind_id']) ? $indicadorCopiado['ind_id'] : NULL;
            $valorIndicador     = !empty($indicadorCopiado['ipc_valor']) ? $indicadorCopiado['ipc_valor'] : NULL;
        }

		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_carga(ipc_id, ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_copiado, ipc_evaluacion, institucion, year) VALUES('".$codigo."', '".$idcarga."', '".$idIndicador."', '".$valorIndicador."', '".$periodo."', 1, '".$copiado."', '".$evaluacion."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
    }

    /**
     * Este metodo me re-calcula los valores de los indicadores
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
     * @param float     $valorIgualIndicador
    **/
    public static function actualizarValorIndicadores (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        int     $periodo, 
        float   $valorIndicadores
    )
    {
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_indicadores_carga SET ipc_valor='".$valorIndicadores."' WHERE ipc_carga='".$idcarga."' AND ipc_periodo='".$periodo."' AND ipc_creado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
    }

}