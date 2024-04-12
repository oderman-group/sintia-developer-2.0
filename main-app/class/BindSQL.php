<?php

use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

require_once($_SERVER['DOCUMENT_ROOT'] . "/app-sintia/config-general/constantes.php");

class BindSQL
{
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
                    if (is_numeric($parametro)) {
                        $tipoParametro .= 'i';
                    } else {
                        $tipoParametro .= 's';
                    }
                }

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
            include(ROOT_PATH . "/compartido/error-catch-to-report.php");
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
}