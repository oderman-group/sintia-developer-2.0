<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/servicios/GradoServicios.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";
class Grados {

    /**
     * Lista los grados académicos según el estado y tipo proporcionados.
     *
     * @param int    $estado El estado de los grados a listar (1 activo, 0 inactivo u otro estado según la aplicación).
     * @param string $tipo   El tipo de grado académico a listar (opcional, puede ser null para listar todos los tipos).
     *
     * @return mysqli_result Devuelve Un conjunto de resultados (`mysqli_result`)
     *
     * @throws Exception Si hay algún problema durante la ejecución de la consulta SQL, se captura una excepción y se imprime un mensaje de error.
     *
     * @example
     * ```php
     * // Ejemplo de uso para listar todos los grados activos
     * $gradosActivos = listarGrados(1);
     * foreach ($gradosActivos as $grado) {
     *     echo "ID: " . $grado['gra_id'] . ", Nombre: " . $grado['gra_nombre'] . "\n";
     * }
     *
     * // Ejemplo de uso para listar grados activos de un tipo específico
     * $tipoGrado = "TIPO_EJEMPLO";
     * $gradosTipoEjemplo = listarGrados(1, $tipoGrado);
     * foreach ($gradosTipoEjemplo as $grado) {
     *     echo "ID: " . $grado['gra_id'] . ", Nombre: " . $grado['gra_nombre'] . "\n";
     * }
     * ```
     */
    public static function listarGrados($estado = 1, $tipo =null){
        
        global $conexion, $arregloModulos, $config;
        
        $resultado = null;
        $filtro="";
        if(!is_null($tipo)){
            $filtro="AND gra_tipo ='".$tipo."'";
        }

        if( !array_key_exists(10,$arregloModulos) ) { 
            $filtro="AND gra_tipo ='".GRADO_GRUPAL."'";
        }

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados
            WHERE gra_estado IN (1, '".$estado."') AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} 
            ".$filtro."
            ORDER BY gra_vocal
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    /**
     * Obtiene los datos de un grado académico específico.
     *
     * @param string $grado El identificador único del grado académico a obtener.
     *
     * @return mysqli_result Un conjunto de resultados (`mysqli_result`)
     *
     * @throws Exception Si hay algún problema durante la ejecución de la consulta SQL, se captura una excepción y se imprime un mensaje de error.
     *
     * @example
     * ```php
     * // Ejemplo de uso para obtener los datos de un grado específico
     * $idGrado = "ID_EJEMPLO";
     * $datosGrado = obtenerDatosGrados($idGrado);
     * print_r($datosGrado);
     * ```
     */
    public static function obtenerDatosGrados($grado = ''){
        
        global $conexion, $config;
        
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados WHERE gra_id='{$grado}' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

    public static function obtenerGrado($grado = ''){        
            return mysqli_fetch_array(Grados::obtenerDatosGrados($grado));
    }

    /**
     * Obtiene el porcentaje por periodo de un curso.
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $grado
     * @param int $periodo
     * 
     * @return array $resultado
     *
     */
    public static function traerPorcentajePorPeriodosGrados(
        mysqli $conexion,
        array $config,
        string $grado,
        int $periodo,
    ){
        
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grados_periodos
            WHERE gvp_grado='" . $grado . "' AND gvp_periodo='" . $periodo . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

    /**
     * Me elimina la intensidad de una materia en un curso
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $curso
     * @param string $materia
     *
     */
    public static function eliminarIntensidadMateriaCurso(
        mysqli $conexion,
        array $config,
        string $curso,
        string $materia
    ){

        try {
            mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_intensidad_curso WHERE ipc_curso='".$curso."' AND ipc_materia='".$materia."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Me guarda la intensidad de una materia en un curso
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $curso
     * @param string $materia
     * @param string $ih
     *
     */
    public static function guardarIntensidadMateriaCurso(
        mysqli $conexion,
        PDO     $conexionPDO,
        array $config,
        string $curso,
        string $materia,
        string $ih
    ){
        $codigo=Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_intensidad_curso');

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_intensidad_curso(ipc_id, ipc_curso, ipc_materia, ipc_intensidad, institucion, year)VALUES('".$codigo."', '".$curso."','".$materia."','".$ih."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
    }

    /**
     * Me trae la intensidad de una materia en un curso
     *
     * @param mysqli $conexion
     * @param array $config
     * @param string $curso
     * @param string $materia
     * 
     * @return array $resultado
     *
     */
    public static function traerIntensidadMateriaCurso(
        mysqli $conexion,
        array $config,
        string $curso,
        string $materia
    ){
        
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_intensidad_curso WHERE ipc_curso='".$curso."' AND ipc_materia='".$materia."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

}