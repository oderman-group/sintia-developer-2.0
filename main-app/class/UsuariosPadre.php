<?php

class UsuariosPadre {

    public static function nombreCompletoDelUsuario($usuario = 0)
    {

        global $conexion;
        $resultado = "";

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
            WHERE uss_id='".$usuario."'
            ");
            $num = mysqli_num_rows($consulta);
            if($num == 0){
                return "Usuario que no existe: ".$usuario;
            }
            $datos = mysqli_fetch_array($consulta, MYSQLI_BOTH);
            $resultado = strtoupper($datos['uss_nombre']." ".$datos['uss_nombre2']." ".$datos['uss_apellido1']." ".$datos['uss_apellido2']);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

}