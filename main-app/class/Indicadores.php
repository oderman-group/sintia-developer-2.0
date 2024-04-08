<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";

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
        PDO     $conexionPDO, 
        array   $config, 
        string  $idcarga, 
        string  $idIndicador,  
        int     $periodo,  
        array   $POST               = NULL,  
        array   $indicadorCopiado   = NULL, 
        float   $creado, 
        string  $valor = ""
    )
    {
        $codigo             = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_indicadores_carga');
        $copiado            = NULL;
        $evaluacion         = NULL;
        $valorIndicador     = NULL;

        if($POST != NULL){
            $evaluacion     = !empty($POST["saberes"]) ? $POST["saberes"] : NULL;
            $valorIndicador = !empty($valor) ? $valor : (!empty($POST["valor"]) ? $POST["valor"] : NULL);
        }
        
        if($indicadorCopiado != NULL){
            $evaluacion         = !empty($indicadorCopiado['ipc_evaluacion']) ? $indicadorCopiado['ipc_evaluacion'] : NULL;
            $copiado            = !empty($indicadorCopiado['ind_id']) ? $indicadorCopiado['ind_id'] : NULL;
            $valorIndicador     = !empty($indicadorCopiado['ipc_valor']) ? $indicadorCopiado['ipc_valor'] : NULL;
        }

		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_carga(ipc_id, ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_copiado, ipc_evaluacion, institucion, year) VALUES('".$codigo."', '".$idcarga."', '".$idIndicador."', '".$valorIndicador."', '".$periodo."', '".$creado."', '".$copiado."', '".$evaluacion."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
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

    /**
     * Este metodo me trae la relación de una indicador con una carga
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param string    $idIndicador
     * 
     * @return array    $resultado
    **/
    public static function traerRelacionCargaIndicador (
        mysqli  $conexion, 
        array   $config, 
        string  $idCarga, 
        string  $idIndicador
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_carga='".$idCarga."' AND ipc_indicador='".$idIndicador."' AND ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me elimina la relación entre indicador y una carga
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param string    $idIndicador
    **/
    public static function eliminarRelacionCargaIndicador (
        mysqli  $conexion, 
        array   $config, 
        string  $idCarga, 
        string  $idIndicador
    )
    {
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_carga='".$idCarga."' AND ipc_indicador='".$idIndicador."' AND ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me trae los indicador de un periodo de una carga
     * @param mysqli            $conexion
     * @param array             $config
     * @param string            $idcarga
     * @param int               $periodo
     * @param string            $year
     * 
     * @return mysqli_result    $consulta
    **/
    public static function traerCargaIndicadorPorPeriodo (
        mysqli  $conexion, 
        array   $config, 
        string  $idCarga, 
        int     $periodo, 
        string  $year = ""
    )
    {
        $yearConsulta = $_SESSION['bd'];
        if (!empty($year)) {
            $yearConsulta = $year;
        }
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
            INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$yearConsulta}
            WHERE ipc.ipc_carga='".$idCarga."' AND ipc.ipc_periodo='".$periodo."' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$yearConsulta}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae los datos de un indicador
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idIndicador
     * 
     * @return array    $resultado
    **/
    public static function traerDatosIndicador (
        mysqli  $conexion, 
        array   $config,  
        string  $idIndicador
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga aic
            INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=aic.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
            WHERE aic.ipc_id='".$idIndicador."' AND aic.institucion={$config['conf_id_institucion']} AND aic.year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me elimina indicador
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idIndicador
    **/
    public static function eliminarIndicador (
        mysqli  $conexion, 
        array   $config, 
        string  $idIndicador
    )
    {
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_id='" . $idIndicador . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo me trae los indicador generados
     * @param mysqli            $conexion
     * @param array             $config
     * 
     * @return mysqli_result    $consulta
    **/
    public static function consultarIndicadorGenerados (
        mysqli  $conexion, 
        array   $config
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_creado=0 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me trae todos los indicadorde una carga
     * @param mysqli            $conexion
     * @param array             $config
     * @param string            $idCarga
     * @param string            $filtro
     * 
     * @return mysqli_result    $consulta
    **/
    public static function consultarIndicador (
        mysqli  $conexion, 
        array   $config,
        string  $idCarga,
        string  $filtro = "",
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
            INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
            WHERE ipc.ipc_carga='".$idCarga."' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]} $filtro
            ORDER BY ipc.ipc_periodo");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }

        return $consulta;
    }

    /**
     * Este metodo me consulta indicador en un periodo
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idIndicador
     * @param string    $idCarga
     * @param int       $periodo
     * 
     * @return array    $resultado
    **/
    public static function consultaIndicadorPeriodo (
        mysqli  $conexion, 
        array   $config,  
        string  $idIndicador,  
        string  $idCarga,  
        int     $periodo
    )
    {
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga 
            WHERE ipc_indicador='".$idIndicador."' AND ipc_carga='".$idCarga."' AND ipc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }

    /**
     * Este metodo me elimina los indicadores de una carga en un periodo
     * @param mysqli    $conexion
     * @param array     $config
     * @param string    $idcarga
     * @param int       $periodo
    **/
    public static function eliminarCargaIndicadorPeriodo (
        mysqli  $conexion, 
        array   $config, 
        string  $idCarga, 
        int     $periodo
    )
    {
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga WHERE ipc_carga='".$idCarga."' AND ipc_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }

    /**
     * Este metodo guarda la relación de un indicador con su carga
     * @param mysqli    $conexion
     * @param string    $idcarga
    **/
    public static function guardarIndicadorCargaMaxivo (
        mysqli  $conexion,
        string  $datosInsert
    )
    {
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_carga(ipc_id, ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_copiado, institucion, year) VALUES $datosInsert");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
    }

}