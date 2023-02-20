<?php
include("geoiploc.php");
$ip=$_SERVER["REMOTE_ADDR"];
$paisIP=getCountryFromIP($ip);
$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial;
$tiempoMostrar = round($tiempo,3);

$idLogin=null;
if(isset($_SESSION['admin'])){
    $idLogin=$_SESSION['admin'];
}
if(isset($_SESSION['docente'])){
    $idLogin=$_SESSION['docente'];
}
if(isset($_SESSION['acudiente'])){
    $idLogin=$_SESSION['acudiente'];
}

try {
    //HISTORIAL DE ACCIONES
    mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".seguridad_historial_acciones(
        hil_usuario, 
        hil_url, 
        hil_titulo, 
        hil_pais, 
        hil_ip, 
        hil_so, 
        hil_institucion, 
        hil_pagina_anterior, 
        hil_tiempo_carga, 
        hil_usuario_autologin)
    VALUES(
        '".$_SESSION['id']."', 
        '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'].":".__FILE__.":".__LINE__."', 
        '".$idPaginaInterna."', 
        '".$paisIP."', 
        '".$ip."', 
        '".$_SERVER['HTTP_USER_AGENT']."', 
        '".$config['conf_id_institucion']."', 
        '".$_SERVER["HTTP_REFERER"]."', 
        '".$tiempoMostrar."', 
        '".$idLogin."'
    )");
} catch (Exception $e) {
	$lineaError   = __LINE__;
	include("../compartido/error-catch-to-report.php");
}