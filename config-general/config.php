<?php
$tiempo_inicial = microtime(true);
include("../modelo/conexion.php");
$configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$_SESSION["inst"]."' AND conf_agno='".$_SESSION["bd"]."'");
$config = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);

$informacionInstConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion WHERE info_institucion='" . $config['conf_id_institucion'] . "' AND info_year='" . $_SESSION["bd"] . "'");
$informacion_inst = mysqli_fetch_array($informacionInstConsulta, MYSQLI_BOTH);

$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$config['conf_id_institucion']."'");
$datosUnicosInstitucion = mysqli_fetch_array($datosUnicosInstitucionConsulta, MYSQLI_BOTH);
$yearArray = explode(",", $datosUnicosInstitucion['ins_years']);
$yearStart = $yearArray[0];
$yearEnd = $yearArray[1];

//CONFIGURACIÓN GENERAL
$opcionSINO = array ("NO","SI");
$mesesAgno = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
$opcionEstado = array("INACTIVO", "ACTIVO");