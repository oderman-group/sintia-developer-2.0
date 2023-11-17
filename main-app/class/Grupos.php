<?php
class Grupos {

    public static function obtenerDatosGrupos($grupo = 0){
        global $conexion, $config;
        $resultado = [];
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE gru_id='".$grupo."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");            
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
        global $conexion, $config;
        $resultado = [];
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_grupos WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

}