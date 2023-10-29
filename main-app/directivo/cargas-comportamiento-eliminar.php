<?php 
include("session.php"); 

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0152';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "DELETE FROM disiplina_nota WHERE dn_id='".base64_decode($_GET['id'])."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");
	
	echo '<script type="text/javascript">window.location.href="cargas-comportamiento.php?error=ER_DT_3&periodo='.$_GET["periodo"].'&carga='.$_GET["carga"].'&grado='.$_GET["grado"].'&grupo='.$_GET["grupo"].'";</script>';
	exit();