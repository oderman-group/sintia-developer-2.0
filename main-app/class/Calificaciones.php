<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

class Calificaciones {

    /**
     * Este metodo me re-calcula los valores de todas las actividades de un indicador
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
     * @param array     $datosIndicador
    **/
    public static function actualizarValorCalificacionesDeUnIndicador (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        int     $periodo, 
        array   $datosIndicador
    )
    {
		try{
			$consultaActividadesNum=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividades WHERE act_id_tipo='".$datosIndicador['ipc_indicador']."' AND act_id_carga='".$idcarga."' AND act_periodo='".$periodo."' AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
		$actividadesNum = mysqli_num_rows($consultaActividadesNum);

		//Si hay actividades relacionadas al indicador, actualizamos su valor.
		if($actividadesNum>0){
			$valorIgualActividad = ($datosIndicador['ipc_valor']/$actividadesNum);
			try{
				mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_valor='".$valorIgualActividad."' WHERE act_id_tipo='".$datosIndicador['ipc_indicador']."' AND act_id_carga='".$idcarga."' AND act_periodo='".$periodo."' AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}
    }

    /**
     * Este metodo me re-calcula los valores de todas las actividades de una carga
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
    **/
    public static function actualizarValorCalificacionesDeUnaCarga (
        mysqli  $conexion, 
        array   $config, 
        string  $idcarga, 
        int     $periodo
    )
    {
        try{
            $indicadoresConsultaActualizado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga 
            WHERE ipc_carga='".$idcarga."' AND ipc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    
        while($indicadoresDatos = mysqli_fetch_array($indicadoresConsultaActualizado, MYSQLI_BOTH)){
            Calificaciones::actualizarValorCalificacionesDeUnIndicador($conexion, $config, $idcarga, $periodo, $indicadoresDatos);
        }	
    }

}