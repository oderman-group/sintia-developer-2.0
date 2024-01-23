<?php
class Utilidades {

    private static $codigoTemporal;

    /**
     * Obtiene una representación de cadena de un valor.
     *
     * Verifica si el valor proporcionado no es nulo y devuelve el valor como una cadena.
     * En caso de que el valor sea nulo, devuelve una cadena vacía.
     *
     * @param mixed $valor - El valor que se desea convertir a cadena.
     *
     * @return string - Representación de cadena del valor o cadena vacía si el valor es nulo.
     *
     * @example
     * ```php
     * // Ejemplo de uso para obtener la representación de cadena de un valor
     * $resultado = getToString($miVariable);
     * // $resultado contendrá la representación de cadena de $miVariable o una cadena vacía si $miVariable es nulo.
     * ```
     */
    public static  function getToString($valor)
    {   
        // validammos que las variables no sean null 
        if (isset($valor)) {
            return  $valor;
        }else{
            return "";
        }
    
    }

    /**
     * Comprueba si un archivo existe en la ruta especificada.
     *
     * Esta función verifica si el archivo especificado por la ruta existe en el sistema de archivos.
     *
     * @param string $ruta La ruta completa al archivo que se va a comprobar.
     *
     * @return bool Devuelve true si el archivo existe, o false en caso contrario.
     */
    public static  function ArchivoExiste($ruta)
    {   
        if ( file_exists($ruta) ) {
            return  true;
        }

        return false;
    
    }

    /**
     * Generates a unique code based on a given index and a combination of numbers and the current timestamp.
     *
     * @param string $index An optional index to prepend to the generated code.
     * @return string The generated unique code.
     */
    public static function generateCode($index='')
    {
        $key = "";
        $pattern = "1234567890";
        $max = strlen($pattern) - 1;

        // Intentar generar un código único
        do {

            $key = "";
            for ($i = 0; $i < 2; $i++) {

                $key .= substr($pattern, mt_rand(0, $max), 1);

            }

            $code = $index . $key . microtime(true);
            $code = str_replace(['.', ' '], '', $code); // Eliminar punto decimal y espacios

        } while (self::codeExists($code)); // Verificar si el código ya existe

        // Almacenar el código temporalmente
        self::$codigoTemporal = $code;

        return $code;
    }

    /**
     * Verifica si el código ya se genero anteriormente.
     *
     * @param string $code El código a verificar.
     * @return bool true si el código ya existe, false en caso contrario.
     */
    private static function codeExists($code)
    {
        // Verificar si el código existe en la variable temporal
        if ($code === self::$codigoTemporal) {

            return true;

        }

        // Ejemplo: devuelve siempre false, ajusta según tus necesidades.
        return false;
    }
}