<?php
include("../modelo/conexion.php");
$config = mysql_fetch_array(mysql_query("SELECT * FROM configuracion WHERE conf_id=1",$conexion));
$informacion_inst = mysql_fetch_array(mysql_query("SELECT * FROM general_informacion",$conexion));

$datosUnicosInstitucion = mysql_fetch_array(mysql_query("SELECT * FROM ".$baseDatosServicios.".instituciones WHERE ins_id='".$config['conf_id_institucion']."'",$conexion));

//CONFIGURACIÓN GENERAL
$opcionSINO = array ("NO","SI");
$mesesAgno = array("","ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
?>
