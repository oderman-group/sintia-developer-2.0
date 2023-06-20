<?php
class Grupos {

    public static function obtenerDatosGrupos($grupo = 0){
        global $conexion;
        $resultado = [];
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grupos WHERE gru_id=$grupo");            
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }
    public static function obtenerGrupo($grupo = 0){
            $datos=Grupos::obtenerDatosGrupos($grupo);
            $resultado = mysqli_fetch_array($datos);
      
        return $resultado;
    }

    public static function listarGrupos(){
        global $conexion;
        $resultado = [];
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grupos");
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

}