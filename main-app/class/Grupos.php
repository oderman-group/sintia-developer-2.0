<?php
class Grupos {

    public static function capturarInformacionGrupos($grupo){
        global $conexion;
        $resultado = [];
        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grupos WHERE gru_id='".$_REQUEST["grupo"]."'");
        } catch (Exception $e){
            echo "Excepción capturada: ".$e->getMessage();
            exit();
        }
        return $resultado;
    }

}