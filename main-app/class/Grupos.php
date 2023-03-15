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

}