<?php
class Grado {

  public static function capturarInformacionGrado($curso){
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