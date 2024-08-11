<?php

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");

class BindSQL
{
    /**
     * Prepara y ejecuta una consulta SQL con parámetros.
     *
     * @param string $sql La consulta SQL con marcadores de posición.
     * @param array $parametros Un array de valores para reemplazar los marcadores de posición en la consulta.
     * @return mixed|false El resultado de la consulta o false en caso de error.
     */
    public static function prepararSQL(
        string $sql,
        array $parametros,
        $finalizartransacion =true
    ) {
        global $conexion;
        self::iniciarTransacion();       
        try {
            $consulta = mysqli_prepare($conexion, $sql);

            if ($consulta) {
                $tipoParametro = '';
                foreach ($parametros as $parametro) {
                    if (is_int($parametro)) {
                        $tipoParametro .= 'i';
                    } else if (is_float($parametro)) {
                        $tipoParametro .= 'd';
                    } else if (is_string($parametro)) {
                        $tipoParametro .= 's';
                    } else if (is_bool($parametro)) {
                        $tipoParametro .= 'i';
                        $parametro = $parametro ? 1 : 0;
                    } else {
                        $tipoParametro .= 's';
                    }
                }
            
                // Aplicar trim a cada valor en $parametros para eliminar comillas innecesarias
                $parametros = array_map(function($value) {
                    return trim($value, "'");
                }, $parametros);
                
                mysqli_stmt_bind_param($consulta, $tipoParametro, ...$parametros);

                mysqli_stmt_execute($consulta);

                $resultado = mysqli_stmt_get_result($consulta);

                if($finalizartransacion){
                    self::finalizarTransacion();
                }

                return $resultado;
            } else {
                self::revertirTransacion();
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            self::revertirTransacion();
            include(ROOT_PATH . "/main-app/compartido/error-catch-to-report.php");
        }
    }

    // funcion para Iniciar la transacio
        public static function iniciarTransacion() // funcion para realizar transaciones multiples
    {
        global $conexion;
        mysqli_query($conexion, "START TRANSACTION");
    }

    // funcion para finalizar la transacion
    public static function finalizarTransacion() 
    {
        global $conexion;
        mysqli_query($conexion, "COMMIT");
    }

    // funcion para revertir la transacion
    public static function revertirTransacion() 
    {
        global $conexion;
        mysqli_query($conexion, "ROLLBACK");
    }

    // Función para preparar la parte de la actualización de forma segura
    public static function prepararUpdate(string $update){
        // Separar la cadena de actualización en partes clave=valor
        $parts = explode(",", $update);
    
        // Array para almacenar las partes preparadas
        $preparedParts = [];
        // Array para almacenar los valores
        $values = [];
    
        // Iterar sobre cada parte
        foreach ($parts as $part) {
            // Dividir la parte en clave y valor
            $pair = explode("=", $part);
            $key = trim($pair[0]);
            $value = trim($pair[1]);
    
            // Añadir la parte preparada al array
            $preparedParts[] = "{$key}=?";
            $values[] = $value;
        }
    
        // Unir las partes preparadas con comas y retornar
        return [implode(",", $preparedParts), $values];
    }

    /**
     * Prepara la parte de la actualización de una consulta SQL a partir de un array asociativo.
     *
     * Esta función toma un array asociativo donde las claves son los nombres de columnas y los valores son los valores a actualizar.
     * La función devuelve un array con dos elementos:
     * 1. Una cadena con las partes de la actualización preparadas para ser utilizadas en una consulta SQL, separadas por comas.
     * 2. Un array con los valores correspondientes a las partes preparadas.
     *
     * @param array $update Un array asociativo con los nombres de columnas y los valores a actualizar.
     * @return array Un array con dos elementos: la cadena de actualización preparada y el array de valores.
     */
    public static function prepararUpdateConArray(array $update){
    
        // Array para almacenar las partes preparadas
        $preparedParts = [];
        // Array para almacenar los valores
        $values = [];
    
        // Iterar sobre cada parte
        foreach ($update as $key => $value) {
            // Añadir la parte preparada al array
            $preparedParts[] = "{$key}=?";
            $values[] = $value;
        }
    
        // Unir las partes preparadas con comas y retornar
        return [implode(",", $preparedParts), $values];
    }
}