<?php
include("../modelo/conexion.php");
$consultaConfig = $conexion->query("SELECT * FROM configuracion WHERE conf_id=1");
$config = mysqli_fetch_array($consultaConfig, MYSQLI_BOTH);

$consultaInformacionInst = $conexion->query("SELECT * FROM general_informacion");
$informacion_inst = mysqli_fetch_array($consultaInformacionInst, MYSQLI_BOTH);

$consultaDatosUnicosInstitucion = $baseDatosServicios->query("SELECT * FROM instituciones 
WHERE ins_id='".$config['conf_id_institucion']."'");
$datosUnicosInstitucion = mysqli_fetch_array($consultaDatosUnicosInstitucion, MYSQLI_BOTH);

//CONFIGURACIÓN GENERAL
$opcionSINO = array ("NO","SI");
$mesesAgno = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
?>
