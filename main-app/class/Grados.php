<?php
class Grados {

    public static function listarGrados($estado = 1){
        
        global $conexion;
        
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grados
            WHERE gra_estado IN (1, '".$estado."')
            ORDER BY gra_vocal
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

    public static function obtenerDatosGrados($grado = 0){
        
        global $conexion;
        
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grados WHERE gra_id=$grado");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

    public static function capturarInformacionGrados($curso){
        global $conexion;
        $resultado = [];
    
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grados WHERE gra_id='".$_REQUEST["curso"]."'");
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

}