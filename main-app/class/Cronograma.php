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
        $resultado = [];

        try{
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_cronograma WHERE cro_id=? AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición
                mysqli_stmt_bind_param($consulta, "sii", $idCronograma, $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);

                // Obtener el resultado de la consulta
                $resultado = mysqli_stmt_get_result($consulta);

                // Obtener la fila de resultados como un array asociativo
                $resultado = mysqli_fetch_array($resultado, MYSQLI_BOTH);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error al preparar la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }

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
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_cronograma 
                WHERE cro_id_carga=? AND cro_periodo=? AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición
                mysqli_stmt_bind_param($consulta, "siii", $idCarga, $periodo, $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);

                // Obtener el resultado de la consulta
                $resultado = mysqli_stmt_get_result($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error al preparar la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }

        return $resultado;
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
            // Preparar la consulta SQL con marcadores de posición
            $consulta= mysqli_prepare($conexion, "SELECT cro_id, cro_tema, cro_fecha, cro_id_carga, cro_recursos, cro_periodo, cro_color, DAY(cro_fecha) as dia, MONTH(cro_fecha) as mes, YEAR(cro_fecha) as agno FROM " . BD_ACADEMICA . ".academico_cronograma 
            WHERE cro_id_carga=? AND cro_periodo=? AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición
                mysqli_stmt_bind_param($consulta, "siii", $idCarga, $periodo, $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);

                // Obtener el resultado de la consulta
                $resultado = mysqli_stmt_get_result($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error al preparar la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }

        return $resultado;
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
        PDO $conexionPDO, 
        array $config,
        array $POST,
        string $idCarga,
        int $periodo
    ){

        $date = date('Y-m-d', strtotime(str_replace('-', '/', $POST["fecha"])));
        $idInsercion = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_cronograma');
        try{
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "INSERT INTO " . BD_ACADEMICA . ".academico_cronograma(cro_id, cro_tema, cro_fecha, cro_id_carga, cro_recursos, cro_periodo, cro_color, institucion, year) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "ssssssiii", $idInsercion, $POST["contenido"], $date, $idCarga, $POST["recursos"], $periodo, $POST["colorFondo"], $config['conf_id_institucion'], $_SESSION["bd"]);
                
                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error al preparar la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
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
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "UPDATE " . BD_ACADEMICA . ".academico_cronograma SET cro_tema=?, cro_fecha=?, cro_recursos=?, cro_color=? WHERE cro_id=? AND institucion=? AND year=?");
            
            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "ssssiii", $POST["contenido"], $date, $POST["recursos"], $POST["colorFondo"], $POST["idR"], $config['conf_id_institucion'], $_SESSION["bd"]);
                
                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error al preparar la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
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
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "DELETE FROM " . BD_ACADEMICA . ".academico_cronograma WHERE cro_id=? AND institucion=? AND year=?");
            
            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "sii", $idCronograma, $config['conf_id_institucion'], $_SESSION["bd"]);
                
                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error al preparar la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }
    }
}