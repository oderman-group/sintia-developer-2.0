<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

class BindSQL{
    public static function prepararSQL(
        string $sql,
        array $parametros
    ){
        global $conexion;
        
        try{
            $consulta = mysqli_prepare($conexion, $sql);

            if ($consulta) {
                $tipoParametro='';
                foreach ($parametros as $parametro){
                    if(is_numeric($parametro)) {
                        $tipoParametro .= 'i';
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

                return $resultado;
            } else {
                echo "Error en la preparación de la consulta.";
                exit();
            }
        } catch (Exception $e) {
            include(ROOT_PATH."/compartido/error-catch-to-report.php");
        }
    }

    // Función para preparar la parte de la actualización de forma segura
    public static function prepararUpdate(
        string $update
    ){
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
}