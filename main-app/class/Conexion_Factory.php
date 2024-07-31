<?php

class Conexion_Factory {

    public static function newConnection(string $tipo)
    {
        switch($tipo) {
            case 'MYSQL':
                return Conexion::getConexion()->conexion();
            break;
            
            case 'PDO':
                return Conexion::getConexion()->conexionPDO();
            break;

            default:
                throw new UnexpectedValueException(sprintf('Este tipo de conexión no es soportada: %s', $tipo));
            break;
        }
    }
}