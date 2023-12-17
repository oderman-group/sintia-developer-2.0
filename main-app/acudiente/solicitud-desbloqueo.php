<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'AC0031';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{	
	mysqli_query($conexion, "INSERT INTO ".BD_GENERAL.".general_solicitudes(soli_id_recurso, soli_remitente, soli_fecha, soli_mensaje, soli_estado, soli_tipo, soli_institucion, soli_year)
	VALUES('".$_POST["idRecurso"]."', '".$_SESSION["id"]."', now(), '".$_POST["contenido"]."', 1, 1, '".$config['conf_id_institucion']."', '".$_SESSION["bd"]."')");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=110";</script>';
exit();