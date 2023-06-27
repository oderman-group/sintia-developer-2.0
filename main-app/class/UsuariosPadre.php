<?php

class UsuariosPadre {

    public static function nombreCompletoDelUsuario($usuario)
    {
        if (!is_array($usuario)) {
            return '--';
        }
        return strtoupper($usuario['uss_nombre']." ".$usuario['uss_nombre2']." ".$usuario['uss_apellido1']." ".$usuario['uss_apellido2']);
    }

    public static function sesionUsuario($idUsuario)
    {
        global $conexion;

        $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$idUsuario."'");
        $datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
        return $datosUsuarioAuto;
    }
    public static function sesionUsuarioAnio($usuario,$instYear)
    {
        global $conexion;
        $consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM ". $instYear.".usuarios WHERE uss_usuario='".$usuario."' limit 1");
        $datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
        return $datosUsuarioAuto;
    }

    

}