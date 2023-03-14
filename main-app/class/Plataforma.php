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
}