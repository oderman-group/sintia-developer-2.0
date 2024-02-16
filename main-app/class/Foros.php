<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Foros{
    /**
     * Este metodo me consulta los datos de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     * 
     * @return array $resultado
     */
    public static function consultarDatosForos(mysqli $conexion, array $config, string $idForo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE foro_id='".$idForo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }
    
    /**
     * Este metodo me consulta los datos de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     * @param string $carga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function traerForosDisintos(mysqli $conexion, array $config, string $idForo, string $carga, int $periodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro 
            WHERE foro_id_carga='".$carga."' AND foro_periodo='".$periodo."' AND foro_estado=1 AND foro_id!='".$idForo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY foro_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me guarda un foro
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     * @param string $carga
     * @param int $periodo
     */
    public static function guardarForos(mysqli $conexion, array $config, array $POST, string $carga, int $periodo){
        $codigo=Utilidades::generateCode("FORO");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_foro(foro_id, foro_nombre, foro_descripcion, foro_id_carga, foro_periodo, foro_estado, institucion, year)VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$POST["titulo"])."', '".mysqli_real_escape_string($conexion,$POST["contenido"])."', '".$carga."', '".$periodo."', 1, {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return mysqli_insert_id($conexion);
    }
    
    /**
     * Este metodo me consulta los datos de un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $carga
     * @param int $periodo
     * 
     * @return mysqli_result $consulta
     */
    public static function traerForos(mysqli $conexion, array $config, string $carga, int $periodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro 
            WHERE foro_id_carga='".$carga."' AND foro_periodo='".$periodo."' AND foro_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
            ORDER BY foro_id DESC");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me actualiza un foro
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function actualizarForos(mysqli $conexion, array $config, array $POST){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_foro SET foro_nombre='".mysqli_real_escape_string($conexion,$POST["titulo"])."', foro_descripcion='".mysqli_real_escape_string($conexion,$POST["contenido"])."' WHERE foro_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me elimina un foro
     * @param mysqli $conexion
     * @param array $config
     * @param string $idForo
     */
    public static function eliminarForos(mysqli $conexion, array $config, string $idForo){
        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE foro_id='".$idForo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
}