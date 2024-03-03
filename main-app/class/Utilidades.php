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
        return $index."-".self::guidv4();
    }

    /**
     * Generates a version 4 UUID.
     *
     * This function generates a universally unique identifier (UUID) according to RFC 4122,
     * version 4. The UUID generated is based on random or pseudo-random numbers, depending on
     * the availability of the `random_bytes` function in PHP. The version 4 UUID is composed of
     * random digits, with certain bits fixed to indicate the version and variant of the UUID.
     *
     * @param string|null $data Optional. Provide 16 bytes of binary data to use for the UUID generation
     *                          instead of generating random data. Primarily used for testing purposes.
     *
     * @return string Returns a string representation of the UUID, which is 36 characters long,
     *                including four hyphens.
     *
     * @throws Exception If generating random bytes fails.
     *
     * @example
     * echo guidv4();
     * // Output: a randomly generated version 4 UUID, such as "f47ac10b-58cc-4372-a567-0e02b2c3d479".
     */
    public static function guidv4($data = null) {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Obtiene el nombre de la página desde una URL y devuelve su versión codificada en base64.
     *
     * Esta función extrae el nombre de la página PHP de una URL dada. Si el nombre de la página
     * se encuentra, se codifica en base64 antes de devolverlo. Esto puede ser útil para manejar
     * referencias a páginas de manera segura o discreta.
     *
     * @param string $url La URL completa desde la cual extraer el nombre de la página.
     * @return string|null El nombre de la página codificado en base64 si se encuentra un archivo .php, 
     *                     de lo contrario, null si la URL está vacía o no contiene un archivo .php.
     */
    public static function getPageFromUrl($url) {
        if (!empty($url)) {
            $urlArray = explode("/", $url);
            $page     = end($urlArray);

            if(strpos($page, '.php')) {
                return base64_encode($page);
            }
        }
    }

    /**
     * Obtiene el directorio de usuario codificado en base64 desde una URL.
     *
     * Analiza la URL proporcionada para determinar si contiene uno de los directorios válidos
     * especificados. Si se encuentra un directorio válido, se devuelve su nombre codificado en base64.
     * Los directorios válidos están definidos en la lista $directoriosValidos. Si la URL no contiene
     * un directorio válido o si está vacía, la función no devuelve nada.
     *
     * @param string $url La URL de la cual extraer el directorio de usuario.
     * @return string|null El directorio de usuario codificado en base64 si es válido, o null si no lo es.
     */
    public static function getDirectoryUserFromUrl($url) {
        if (!empty($url)) {
            $directoriosValidos = [
                'directivo', 'docente', 'acudiente', 'estudiante'
            ];
            $urlArray     = explode("/", $url);
            $cantElements = count($urlArray);

            if ($cantElements > 2) {
                $cantElements = $cantElements  - 2;
            }

            $directorio = $urlArray[$cantElements];

            if (in_array($directorio, $directoriosValidos)) {
                return base64_encode($directorio);
            }
        }
    }
}