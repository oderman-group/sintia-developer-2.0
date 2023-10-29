<?php

class Conexion {

    /**
     * Esta función establece la conexión PDO a la base de datos
     * @param $servidorConexion string
     * @param $usuarioConexion  string
     * @param $claveConexion    string
     * @param $bdActual         string
     */
    public function conexionPDO($servidorConexion, $usuarioConexion, $claveConexion, $bdActual)
    {

        try {
            // Crear una instancia de PDO
            $conexionPDO = new PDO("mysql:host=$servidorConexion;dbname=$bdActual", $usuarioConexion, $claveConexion);

            // Establecer el modo de error PDO a excepciones
            $conexionPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conexionPDO;

        } catch (PDOException  $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return null;
        }
    }

    /**
     * Esta función establece la conexión MYSQLI a la base de datos
     * @param $servidorConexion string
     * @param $usuarioConexion  string
     * @param $claveConexion    string
     * @param $bdActual         string
     */
    public function conexion($servidorConexion, $usuarioConexion, $claveConexion, $bdActual)
    {

        try {
            $conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $bdActual);

            return $conexion;

        } catch (Exception  $e) {
            echo "Excepción capturada: " . $e->getMessage();
            return null;
        }
    }

}