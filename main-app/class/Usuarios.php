<?php

class Usuarios {

    public static function validarExistenciaUsuario($usuario = 0)
    {

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."' || uss_email='".$usuario."') AND uss_bloqueado=0
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }


}