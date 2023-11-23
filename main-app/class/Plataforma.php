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
            $configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_id_institucion='".$_SESSION["idInstitucion"]."' AND conf_agno='".$_SESSION["bd"]."'");
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

    /**
     * Esta función sirve para guardar los errores que sucedan durante
     * la ejecución de código, sin mostrar ninguna interface al usuario.
     * 
     * @param $e Exception
     */
    public static function soloRegistroErrores(Exception $e) {

        $numError     = $e->getCode();
        $lineaError   = $e->getLine();
        $aRemplezar   = array("'", '"', "#", "´");
        $enRemplezo   = array("\'", "|", "\#", "\´");
        $detalleError = str_replace($aRemplezar, $enRemplezo, $e->getMessage());
        $request_data = json_encode($_REQUEST);
        global $conexion;
        global $baseDatosServicios;
        global $config;
        $request_data_sanitizado = mysqli_real_escape_string($conexion, $request_data);

        try {
            mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".reporte_errores(rperr_numero, rperr_fecha, rperr_ip, rperr_usuario, rperr_pagina_referencia, rperr_pagina_actual, rperr_so, rperr_linea, rperr_institucion, rperr_error, rerr_request, rperr_year)
            VALUES('".$numError."', now(), '".$_SERVER["REMOTE_ADDR"]."', '".$_SESSION["id"]."', '".$_SERVER['HTTP_REFERER']."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$_SERVER['HTTP_USER_AGENT']."', '".$lineaError."', '".$config['conf_id_institucion']."','".$detalleError."', '".$request_data_sanitizado."', '".$_SESSION["bd"]."')");
            $idReporteError = mysqli_insert_id($conexion);
            return $idReporteError;
        } catch (Exception $e) {
            return "Hay un inconveniente al guardar el error: ".$e->getMessage();
        }
    }

}