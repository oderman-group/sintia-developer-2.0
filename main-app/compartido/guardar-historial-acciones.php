<?php
include("geoiploc.php");
$ip=$_SERVER["REMOTE_ADDR"];
$paisIP=getCountryFromIP($ip);
$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial;
$tiempoMostrar = round($tiempo,3);
//HISTORIAL DE ACCIONES

$id=$_SESSION['id'];
if (isset($_SESSION["admin"])) {
    $id=$_SESSION["admin"];
    $admin=$_SESSION['id'];
}

mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_pais, hil_ip, hil_so, hil_institucion, hil_pagina_anterior, hil_tiempo_carga, hil_usuario_autologin)
VALUES('".$id."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$idPaginaInterna."', '".$paisIP."', '".$ip."', '".$_SERVER['HTTP_USER_AGENT']."', '".$config['conf_id_institucion']."', '".$_SERVER["HTTP_REFERER"]."', '".$tiempoMostrar."', '".$admin."')");