<?php
class Grados {

    public static function listarGrados($estado = 1){
        
        global $conexion;
        
        $resultado = [];

        try {
            $resultado = mysqli_query($conexion, "SELECT * FROM academico_grados AS G1
            LEFT JOIN academico_grados AS G2 ON G2.gra_id=G1.gra_grado_siguiente
            WHERE G1.gra_estado IN (1, '".$estado."')
            ORDER BY G1.gra_vocal
            ");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;
    }

}