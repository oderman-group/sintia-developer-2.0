<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");

class BindSQL{
    public static function prepararSQL(
        string $sql,
        array $parametros
    )
    {
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
}