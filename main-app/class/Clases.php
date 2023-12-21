<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Clases{
    /**
     * Este metodo me trae las unidades de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $pertiodo
     * 
     * @return mysqli_result $consulta
     */
    public static function consultarUnidades(mysqli $conexion, array $config, string $idCarga, int $pertiodo){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_unidades 
            WHERE uni_id_carga='" . $idCarga . "' AND uni_periodo='" . $pertiodo . "' AND uni_eliminado!=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $consulta;
    }
    
    /**
     * Este metodo me trae las unidades de una carga exceptando la unidad actual
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $pertiodo
     * @param int $idR
     * 
     * @return mysqli_result $consulta
     */
    public static function consultarUnidadesDiferentes(mysqli $conexion, array $config, string $idCarga, int $pertiodo, int $idR){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_unidades 
            WHERE uni_id_carga='" . $idCarga . "' AND uni_periodo='" . $pertiodo . "' AND uni_eliminado!=1 AND id_nuevo!='" . $idR . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $consulta;
    }

    /**
     * Este metodo me trae los datos de una unidad
     * @param mysqli $conexion
     * @param int $idR
     * 
     * @return array $resultado
     */
    public static function consultarUnidadesPorID( mysqli $conexion, int $idR){
        $resultado=[];
        try{
            $consulta=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_unidades WHERE id_nuevo='".$idR."'");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        return $resultado;
    }

    /**
     * Este metodo guarda una unidad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $pertiodo
     * @param array $POST
     */
    public static function guardarUnidades( mysqli $conexion, array $config, string $idCarga, int $pertiodo, array $POST){
        $codigo=Utilidades::generateCode("UNI");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_unidades (uni_id, uni_nombre,uni_id_carga,uni_periodo,uni_descripcion, institucion, year)VALUES('".$codigo."', '".$POST["nombre"]."','".$idCarga."','".$pertiodo."','".$POST["contenido"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo actualiza una unidad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $pertiodo
     * @param array $POST
     */
    public static function actualizarUnidades( mysqli $conexion, array $config, string $idCarga, int $pertiodo, array $POST){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_unidades SET uni_nombre='".$POST["nombre"]."', uni_id_carga='".$idCarga."', uni_periodo='".$pertiodo."', uni_descripcion='".$POST["contenido"]."' WHERE id_nuevo='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo elimina una unidad
     * @param mysqli $conexion
     * @param array $config
     * @param array $GET
     */
    public static function eliminarUnidades( mysqli $conexion, array $config, array $GET){
        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_unidades SET uni_eliminado=1 WHERE id_nuevo='".base64_decode($GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
}