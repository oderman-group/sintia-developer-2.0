<?php
include("session.php");
$idPaginaInterna = 'DT0204';
require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");

include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (empty($_POST["motivoCancelacion"])) {
	include("../compartido/guardar-historial-acciones.php");
	$msj = ' Motivo de cancelacion no valido, verificar que el campo este lleno';
	$url = $_SERVER["HTTP_REFERER"] . '?error=ER_DT_15&msj=' . $msj;;
	echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
	exit();
}


try {
	mysqli_query(
		$conexion,
		"INSERT INTO " . $baseDatosServicios . ".solicitud_cancelacion (
			  solcan_fecha_creacion, 
			  solcan_usuario, 
			  solcan_institucion, 
			  solcan_motivo, 
			  solcan_estado)
    VALUES(       
        NOW(), 
        '" . $datosUsuarioActual['uss_id'] . "', 
        '" . $datosUnicosInstitucion['ins_id'] . "', 
        '" . $_POST["motivoCancelacion"] . "', 
        '" . SOLICITUD_CANCELACION_PENDIENTE . "'
    )"
	);
	$idRegistro = mysqli_insert_id($conexion);
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

$data = [
	'institucion_id'   => $datosUnicosInstitucion['ins_id'],
	'usuario_email'    => $datosUnicosInstitucion['ins_email_contacto'],
	'usuario_nombre'    => $datosUnicosInstitucion['ins_contacto_principal'],
	'solicitud_usuario'    => $datosUsuarioActual['uss_nombre'].' '.$datosUsuarioActual['uss_apellido1'],
	'solicitud_id'    => $idRegistro,
	'usuario_email2'    => $datosUsuarioActual['uss_email']
];
$asunto = 'Solicitud de cancelacion';
$bodyTemplateRoute = ROOT_PATH.'/config-general/template-email-solicitud-cancelacion.php';

EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute,null,null);


include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="solicitud-cancelacion.php?success=SC_DT_1&id=' . $idRegistro . '";</script>';
exit();
