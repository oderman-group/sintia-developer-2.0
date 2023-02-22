<?php

class TipoUsuario {

    public static function listarTiposUsuarios()
    {

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $resultado     = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_perfiles");
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

}