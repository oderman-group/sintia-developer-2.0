<?php

class Usuarios {

    public static function obtenerDatosUsuario($usuario = 0)
    {

        global $conexion;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."')
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                echo "Este usuario no existe";
                exit();
            }
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    public static function validarExistenciaUsuario($usuario = 0)
    {

        global $conexion;
        $num = 0;

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE (uss_id='".$usuario."' || uss_usuario='".$usuario."' || uss_email='".$usuario."')
            ");
            $num = mysqli_num_rows($consulta);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $num;

    }


}