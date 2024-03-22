<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
class Unidades{
    /**
     * Este metodo me trae las unidades de una carga
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * 
     * @return mysqli_result|false $consulta
     */
    public static function consultarUnidades(mysqli $conexion, array $config, string $idCarga, int $periodo)
    {
        try {
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_unidades WHERE uni_id_carga=? AND uni_periodo=? AND uni_eliminado!=1 AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "siii", $idCarga, $periodo, $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);

                // Obtener el resultado de la consulta
                $resultado = mysqli_stmt_get_result($consulta);

                return $resultado;
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }
    }
    
    /**
     * Este metodo me trae las unidades de una carga exceptando la unidad actual
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * @param int $idR
     * 
     * @return mysqli_result|false $consulta
     */
    public static function consultarUnidadesDiferentes(mysqli $conexion, array $config, string $idCarga, int $periodo, int $idR)
    {
        try {
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_unidades WHERE uni_id_carga=? AND uni_periodo=? AND uni_eliminado!=1 AND id_nuevo!=? AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "siiii", $idCarga, $periodo, $idR, $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);

                // Obtener el resultado de la consulta
                $resultado = mysqli_stmt_get_result($consulta);

                return $resultado;
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo me trae los datos de una unidad por su ID
     * @param mysqli $conexion
     * @param int $idR
     * 
     * @return array $resultado
     */
    public static function consultarUnidadesPorID(mysqli $conexion, int $idR)
    {
        $resultado = [];
        try {
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "SELECT * FROM " . BD_ACADEMICA . ".academico_unidades WHERE id_nuevo=?");

            if ($consulta) {
                // Vincular el valor de la variable al marcador de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "i", $idR);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);

                // Obtener el resultado de la consulta
                $resultadoC = mysqli_stmt_get_result($consulta);
                $resultado = mysqli_fetch_array($resultadoC, MYSQLI_BOTH);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error en la preparación de la consulta.";
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
     * Este metodo guarda una unidad
     * @param mysqli $conexion
     * @param array $config
     * @param string $idCarga
     * @param int $periodo
     * @param array $POST
     */
    public static function guardarUnidades(mysqli $conexion, PDO $conexionPDO, array $config, string $idCarga, int $periodo, array $POST)
    {
        $codigo = Utilidades::getNextIdSequence($conexionPDO, BD_ACADEMICA, 'academico_unidades');
        try {
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "INSERT INTO " . BD_ACADEMICA . ".academico_unidades (uni_id, uni_nombre, uni_id_carga, uni_periodo, uni_descripcion, institucion, year) VALUES (?, ?, ?, ?, ?, ?, ?)");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "sssissi", $codigo, $POST["nombre"], $idCarga, $periodo, $POST["contenido"], $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
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
    public static function actualizarUnidades(mysqli $conexion, array $config, string $idCarga, int $pertiodo, array $POST)
    {
        try {
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "UPDATE " . BD_ACADEMICA . ".academico_unidades SET uni_nombre=?, uni_id_carga=?, uni_periodo=?, uni_descripcion=? WHERE id_nuevo=? AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "ssissii", $POST["nombre"], $idCarga, $pertiodo, $POST["contenido"], $POST["idR"], $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Este metodo elimina una unidad
     * @param mysqli $conexion
     * @param array $config
     * @param array $GET
     */
    public static function eliminarUnidades(mysqli $conexion, array $config, array $GET)
    {
        try {
            // Preparar la consulta SQL con marcadores de posición
            $consulta = mysqli_prepare($conexion, "UPDATE " . BD_ACADEMICA . ".academico_unidades SET uni_eliminado=1 WHERE id_nuevo=? AND institucion=? AND year=?");

            if ($consulta) {
                // Vincular los valores de las variables a los marcadores de posición en la consulta preparada
                mysqli_stmt_bind_param($consulta, "sii", base64_decode($GET["idR"]), $config['conf_id_institucion'], $_SESSION["bd"]);

                // Ejecutar la consulta preparada
                mysqli_stmt_execute($consulta);
            } else {
                // Si la preparación de la consulta falla, mostrar un mensaje de error
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            // Manejar la excepción
            echo "Excepción capturada: " . $e->getMessage();
            exit();
        }
    }
}