<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
$conexionBaseDatosServicios = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

try {
    mysqli_query($conexionBaseDatosServicios, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_so, hil_pagina_anterior, hil_pais, hil_institucion)VALUES('".$_GET["usuario"]."', '".$_GET["urlActual"]."', '".$_GET["idPaginaInterna"]."', now(),'".php_uname()."','".$_SERVER['HTTP_REFERER']."', '".$_GET['countryCity']."', '".$_GET['institucion']."')");
} catch(Exception $e) {
    echo "hubo_error:".$e;
}
