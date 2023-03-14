<?php

class UsuariosPadre {

    public static function nombreCompletoDelUsuario(array $usuario)
    {
        return strtoupper($usuario['uss_nombre']." ".$usuario['uss_nombre2']." ".$usuario['uss_apellido1']." ".$usuario['uss_apellido2']);
    }

}