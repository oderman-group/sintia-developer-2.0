<?php

class Plataforma {

    public $colorUno  = '#6017dc';
    public $colorDos  = '#41c4c4';
    public $colorTres = '#56ffe4';
    public $logo      = 'https://main.plataformasintia.com/app-sintia/main-app/sintia-logo-2023.png';

    public static function mostrarModalTerminos($i = 0)
    {

        global $conexion, $baseDatosServicios;
        $resultado = [];

        try {
            $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".terminos_tratamiento_politica WHERE ttp_id=$i");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    /**
     * Este consulta la información de la plataforma sintia y la
     * disponibiliza
     * 
     * @return array
     */
    public static function infoContactoSintia()
    {

        global $conexion, $baseDatosServicios;
        $conexionReal = $conexion;

        if(!$conexion) {
            global $servidorConexion, $usuarioConexion, $claveConexion;
            $conexionReal = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);
        }

        $resultado = [];

        try {
            $consulta = mysqli_query($conexionReal, "SELECT * FROM ".$baseDatosServicios.".datos_contacto WHERE dtc_id=1");
            $resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $resultado;

    }

    /**
     * Este metodo sirve para consultar la configuración de la institución
     * 
     * @return array
     */
    public static function sesionConfiguracion()
    {

        global $conexion, $baseDatosServicios;

        $config = [];

        try {
            $configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$_SESSION["inst"]."' AND conf_agno='".$_SESSION["bd"]."'");
            $config = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);
        } catch (Exception $e) {
            echo "Excepción catpurada: ".$e->getMessage();
            exit();
        }

        return $config;

    }

    public static function getConexion() {
        global $conexion;

        return $conexion;
    }
}