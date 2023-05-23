<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'AC0031';
include("../compartido/historial-acciones-guardar.php");

//SOLICITUD DE DESBLOQUEO
if($_POST["id"]==1){

try{	
	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_solicitudes(soli_id_recurso, soli_remitente, soli_fecha, soli_mensaje, soli_estado, soli_tipo, soli_institucion, soli_year)
	VALUES('".$_POST["idRecurso"]."', '".$_SESSION["id"]."', now(), '".$_POST["contenido"]."', 1, 1, '".$config['conf_id_institucion']."', '".$_SESSION["bd"]."')");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=110";</script>';
	exit();
}
//ENCUESTA RESERVA DE CUPO
if($_POST["id"]==2){

try{	
	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario, genc_institucion, genc_year)
	VALUES('".$_POST["idEstudiante"]."', now(), '".$_POST["respuesta"]."', '".$_POST["motivo"]."','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=111";</script>';
	exit();
}
//FIRMAR ASPECTOS
if($_POST["id"]==3){

try{
	mysqli_query($conexion, "UPDATE disiplina_nota SET dn_aprobado=1, dn_fecha_aprobado=now()
    WHERE dn_cod_estudiante=" . $_POST["estudiante"] . " AND dn_periodo='" . $_POST["periodo"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="'.$_SERVER["HTTP_REFERER"].'";</script>';

	exit();

}

//========================================== GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET  GET GET GET GET GET GET GET GET GET GET GET GET GET ======================================================
//FIRMA DIGITAL DE LOS REPORTES
if($_GET["get"]==1){

	try{
		mysqli_query($conexion, "UPDATE disciplina_reportes SET dr_aprobacion_acudiente=1, dr_aprobacion_acudiente_fecha=now(), dr_comentario='".$_GET["comentario"]."' WHERE dr_id='".$_GET["id"]."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();
}

$_GET["get"] == 0;
include("../compartido/guardar-historial-acciones.php");
//EN CASO DE QUE NO ENTRE POR NINGUNA DE LAS ANTERIORES
echo '<script type="text/javascript">window.location.href="https://plataformasintia.com?error=1";</script>';
exit();
?>