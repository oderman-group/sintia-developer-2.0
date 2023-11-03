<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'AC0037';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{	
	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario, genc_institucion, genc_year)
	VALUES('".$_POST["idEstudiante"]."', now(), '".$_POST["respuesta"]."', '".$_POST["motivo"]."','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=111";</script>';
exit();