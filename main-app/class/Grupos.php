<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Grupos {

    /**
     * Obtiene los datos de un grupo académico específico.
     * 
     * @param string $grupo - El identificador único del grupo académico a obtener.
     *
     * @return mysqli_result - Devuelve un conjunto de resultados (fila) obtenido de la base de datos con la información del grupo académico correspondiente al identificador proporcionado.
     *
     * @throws Exception - Si hay algún problema durante la ejecución de la consulta SQL, se captura una excepción y se imprime un mensaje de error.
     *
     * @example
     * ```php
     * // Ejemplo de uso para obtener los datos de un grupo específico
     * $idGrupo = "ID_EJEMPLO";
     * $datosGrupo = obtenerDatosGrupos($idGrupo);
     * print_r($datosGrupo);
     * ```
     */
    public static function obtenerDatosGrupos($grupo = ''){
        global $conexion, $config;
        $resultado = [];
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE gru_id='".$grupo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");            
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

    /**
     * Obtiene un grupo académico específico a partir de su identificador único.
     *
     * @param int $grupo - El identificador único del grupo académico a obtener.
     *
     * @return array - Devuelve un array asociativo con la información del grupo académico correspondiente al identificador proporcionado.
     *
     * @throws Exception - Si hay algún problema durante la ejecución de la consulta SQL, se captura una excepción y se imprime un mensaje de error.
     *
     * @example
     * ```php
     * // Ejemplo de uso para obtener un grupo académico por su identificador
     * $idGrupo = 123; // Reemplazar con el identificador real del grupo
     * $grupoObtenido = obtenerGrupo($idGrupo);
     * print_r($grupoObtenido);
     * ```
     */
    public static function obtenerGrupo($grupo = ''){
            $datos=Grupos::obtenerDatosGrupos($grupo);
            $resultado = mysqli_fetch_array($datos);

        return $resultado;
    }

    /**
     * Lista todos los grupos académicos existentes en la institución.
     *
     * @return mysqli_result - Un conjunto de resultados (`mysqli_result`)
     *
     * @throws Exception - Si hay algún problema durante la ejecución de la consulta SQL, se captura una excepción y se imprime un mensaje de error.
     *
     * @example
     * ```php
     * // Ejemplo de uso para listar todos los grupos académicos
     * $grupos = listarGrupos();
     * print_r($grupos);
     * ```
     */
    public static function listarGrupos(){
        global $conexion, $config;
        $resultado = null;
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }
    
    /**
     * Este metodo me trae los grupos de la institución
     * @param mysqli $conexion
     * @param array $config
     * 
     * @return mysqli_result $consulta
     */
    public static function traerGrupos(mysqli $conexion, array $config){
        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Este metodo me guarda un grupo
     * @param mysqli $conexion
     * @param PDO $conexionPDO
     * @param array $config
     * @param array $POST
     */
    public static function guardarGrupos(mysqli $conexion, PDO $conexionPDO, array $config, array $POST){
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_grupos');
        try{
            mysqli_query(
                $conexion,
                "INSERT INTO ".BD_ACADEMICA.".academico_grupos (
                    gru_id, 
                    gru_codigo, 
                    gru_nombre, 
                    institucion, 
                    year
                )	
                VALUES(
                    '".$codigo."', 
                    '" . $POST["codigoG"] . "',
                    '" . $POST["nombreG"] . "', 
                    {$config['conf_id_institucion']}, 
                    {$_SESSION["bd"]}
                )"
            );

            //Eliminar el archivo para limpiar la caché
            $archivoCache = 'grupos.json';
            if (file_exists($archivoCache)) {
                unlink($archivoCache);
            }

            return $codigo;

        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me actualiza un grupo
     * @param mysqli $conexion
     * @param array $config
     * @param array $POST
     */
    public static function actualizarGrupos(mysqli $conexion, array $config, array $POST){
        try{
            mysqli_query(
                $conexion,
                "UPDATE ".BD_ACADEMICA.".academico_grupos SET
                    gru_codigo =".$POST['codigoG'].", 
                    gru_nombre  ='".$POST['nombreG']."'
                    WHERE gru_id='".$POST["id"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"
            );
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }


    public static function listarGruposCache() {
        $archivoCache = 'grupos.json';

        if (file_exists($archivoCache)) {
            $json_data = file_get_contents($archivoCache);
            $data = json_decode($json_data, true);
            return $data;
        } else {
            return [];
        }
    }



}