<?php
include("session.php");
$idPaginaInterna = 'DV0040';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");

include("../compartido/historial-acciones-guardar.php");
Modulos::validarAccesoDirectoPaginas();

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (empty($_POST["respuesta"])) {
	include("../compartido/guardar-historial-acciones.php");
	$msj = 'Verificar que el campo respuesta este lleno';
	$url = $_SERVER["HTTP_REFERER"] . '&error=ER_DT_15&msj=' . base64_encode($msj);
	echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
	exit();
}


try {	
			mysqli_query(
			$conexion,
			"UPDATE  " . $baseDatosServicios . ".solicitud_cancelacion 
			SET solcan_respuesta ='".$_POST["respuesta"]."',
			solcan_responsable ='".$datosUsuarioActual["uss_id"]."',
			solcan_ultima_actualizacion = NOW(),
			solcan_estado ='".$_POST["estado"]."'
			WHERE 
			solcan_id  ='".$_POST["id"]."'"
			);
			$idRegistro = $_POST["id"];
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

if($_POST["estado"]!=SOLICITUD_CANCELACION_PENDIENTE){
$data = [
	'usuario_email'    => $_POST['ins_email_contacto'],
	'usuario_nombre'    => $_POST['ins_contacto'],
	'solicitud_usuario'    => $_POST['ins_id'],
	'solicitud_id'    => $idRegistro,
	'institucion_id' => $_POST["ins_id"],
	'solicitud_estado'    => $_POST["estado"],
	'solicitud_respuesta'    => $_POST["respuesta"],
	'solicitud_responsable'    => $datosUsuarioActual["uss_nombre"],
	'usuario2_email'    => $datosUsuarioActual['uss_email'],
	'usuario2_nombre'    => $datosUsuarioActual['uss_nombre']
];


$asunto = 'Respuesta solicitud de cancelacion';
$bodyTemplateRoute = ROOT_PATH.'/config-general/template-email-respuesta-solicitud.php';

EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute,null,null);
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="dev-solicitudes-cancelacion.php?success=SC_DT_2&id=' . $idRegistro . '";</script>';
exit();
