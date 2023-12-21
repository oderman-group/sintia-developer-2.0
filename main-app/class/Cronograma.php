<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Cronograma {
    
    /**
     * Buscar cronograma por el id.
     *
     * @param mysqli    $conexion Objeto de conexión a la base de datos.
     * @param array     $config Configuraciones de la aplicación.
     * @param string    $idCronograma Identificador del cronograma.
     *
     */
    public static function buscarCronograma(
        mysqli $conexion, 
        array $config,
        string $idCronograma
    ){
        $resultado= [];

        try{
            $consulta= mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cronograma WHERE cro_id='".$idCronograma."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);

        return $resultado;
    }
    
    /**
     * Traer todos los datos de un cronograma.
     *
     * @param mysqli    $conexion Objeto de conexión a la base de datos.
     * @param array     $config Configuraciones de la aplicación.
     * @param string    $idCarga Identificador de la carga académica.
     * @param int       $periodo Identificador del periodo.
     *
     */
    public static function traerDatosCompletosCronograma(
        mysqli $conexion, 
        array $config,
        string $idCarga,
        int $periodo
    ){

        try{
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cronograma 
            WHERE cro_id_carga='".$idCarga."' AND cro_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Traer algunos datos de un cronograma.
     *
     * @param mysqli    $conexion Objeto de conexión a la base de datos.
     * @param array     $config Configuraciones de la aplicación.
     * @param string    $idCarga Identificador de la carga académica.
     * @param int       $periodo Identificador del periodo.
     *
     */
    public static function traerDatosCronograma(
        mysqli $conexion, 
        array $config,
        string $idCarga,
        int $periodo
    ){

        try{
            $consulta= mysqli_query($conexion, "SELECT cro_id, cro_tema, cro_fecha, cro_id_carga, cro_recursos, cro_periodo, cro_color, DAY(cro_fecha) as dia, MONTH(cro_fecha) as mes, YEAR(cro_fecha) as agno FROM ".BD_ACADEMICA.".academico_cronograma 
            WHERE cro_id_carga='".$idCarga."' AND cro_periodo='".$periodo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $consulta;
    }
    
    /**
     * Guardar cronograma.
     *
     * @param mysqli    $conexion Objeto de conexión a la base de datos.
     * @param array     $config Configuraciones de la aplicación.
     * @param array     $POST Identificador del cronograma.
     * @param string    $idCarga Identificador de la carga.
     * @param int       $periodo Identificador del periodo.
     *
     */
    public static function guardarCronograma(
        mysqli $conexion, 
        array $config,
        array $POST,
        string $idCarga,
        int $periodo
    ){

        $date = date('Y-m-d', strtotime(str_replace('-', '/', $POST["fecha"])));
        
        $idInsercion=Utilidades::generateCode("CRO");
        try{
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_cronograma(cro_id, cro_tema, cro_fecha, cro_id_carga, cro_recursos, cro_periodo, cro_color, institucion, year)"." VALUES('" .$idInsercion . "', '".mysqli_real_escape_string($conexion,$POST["contenido"])."', '".$date."', '".$idCarga."', '".$POST["recursos"]."', '".$periodo."', '".$POST["colorFondo"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $idInsercion;
    }
    
    /**
     * Actualizar cronograma.
     *
     * @param mysqli    $conexion Objeto de conexión a la base de datos.
     * @param array     $config Configuraciones de la aplicación.
     * @param array     $POST Identificador del cronograma.
     *
     */
    public static function actualizarCronograma(
        mysqli $conexion, 
        array $config,
        array $POST
    ){

        $date = date('Y-m-d', strtotime(str_replace('-', '/', $POST["fecha"])));

        try{
            mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cronograma SET cro_tema='".mysqli_real_escape_string($conexion,$POST["contenido"])."', cro_fecha='".$date."', cro_recursos='".$POST["recursos"]."', cro_color='".$POST["colorFondo"]."' 
            WHERE cro_id='".$POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
    
    /**
     * Eliminar cronograma.
     *
     * @param mysqli    $conexion Objeto de conexión a la base de datos.
     * @param array     $config Configuraciones de la aplicación.
     * @param string    $idCronograma Identificador del cronograma.
     *
     */
    public static function eliminarCronograma(
        mysqli $conexion, 
        array $config,
        string $idCronograma
    ){

        try{
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_cronograma WHERE cro_id='".$idCronograma."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }
}