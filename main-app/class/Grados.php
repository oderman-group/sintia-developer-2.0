<?php
require_once("../class/servicios/GradoServicios.php");
class Grados {

    public static function listarGrados($estado = 1,$tipo =null){
        
        global $conexion;
        
        $resultado = [];
        $filtro="";
        if(!is_null($tipo)){
            $filtro="AND gra_tipo ='".$tipo."'";
        }
         
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grados
            WHERE gra_estado IN (1, '".$estado."') 
            ".$filtro."
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

}