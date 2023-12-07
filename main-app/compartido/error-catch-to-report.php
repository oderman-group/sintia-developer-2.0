<?php
$numError     = $e->getCode();
$lineaError   = $e->getLine();
$aRemplezar   = array("'", '"', "#", "´");
$enRemplezo   = array("\'", "|", "\#", "\´");
$detalleError = str_replace($aRemplezar, $enRemplezo, $e->getMessage());
$request_data = json_encode($_REQUEST);
global $conexion;
global $baseDatosServicios;
global $config;
global $datosUsuarioActual;
$request_data_sanitizado = mysqli_real_escape_string($conexion, $request_data);

require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");

$contenidoMsg = '
	<p>A user has got an error:</p>
	<p>
		<b>Institution:</b> '.$config['conf_id_institucion'].'<br>
		<b>Year:</b> '.$_SESSION["bd"].'<br>
		<b>User:</b> '.$_SESSION["id"].' - '.$datosUsuarioActual['uss_nombre'].'<br>
		<b>Date:</b> '.date("d/m/Y h:i:s").'<br>
		<b>Cod. Error:</b> '.$numError.'<br>
		<b>Current URL:</b> '.$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'].'<br>
		<b>URL Reference:</b> '.$_SERVER['HTTP_REFERER'].'<br>
		<b>Error detail:</b> '.$detalleError.'<br>
		<b>Line of error:</b> '.$lineaError.'<br>
		<b>Request:</b> '.$request_data_sanitizado.'<br>
		<b>Error trace:</b> '.$e->getTraceAsString().'<br>
	</p>
	';

$data = [
	'usuario_email'    => 'info@oderman-group.com',
	'usuario_nombre'   => 'Jhon Oderman',
	'usuario2_email'   => 'enuarlara@oderman-group.com',
	'usuario2_nombre'  => 'Enuar Lara',
	'institucion_id'   => $config['conf_id_institucion'],
	'institucion_agno' => $_SESSION["bd"],
	'usuario_id'       => $_SESSION["id"],
	'contenido_msj'    => $contenidoMsg
];
$asunto = 'Error report - COD: '.$numError;
$bodyTemplateRoute = ROOT_PATH.'/config-general/plantilla-email-2.php';

EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute, null, null);

try {
	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".reporte_errores(rperr_numero, rperr_fecha, rperr_ip, rperr_usuario, rperr_pagina_referencia, rperr_pagina_actual, rperr_so, rperr_linea, rperr_institucion, rperr_error, rerr_request, rperr_year)
	VALUES('".$numError."', now(), '".$_SERVER["REMOTE_ADDR"]."', '".$_SESSION["id"]."', '".$_SERVER['HTTP_REFERER']."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', '".$_SERVER['HTTP_USER_AGENT']."', '".$lineaError."', '".$config['conf_id_institucion']."','".$detalleError."', '".$request_data_sanitizado."', '".$_SESSION["bd"]."')");
	$idReporteError = mysqli_insert_id($conexion);
} catch (Exception $e) {
	echo "Hay un inconveniente al guardar el error: ".$e->getMessage();
	exit();
}

?>
	<div style="font-family: Consolas; padding: 10px; background-color: black; color:greenyellow;">
		<strong>ERROR DE EJECUCIÓN</strong><br>
		Lo sentimos, ha ocurrido un error.<br>
		Pero no se preocupe, hemos reportado este error automáticamente al personal de soporte de la plataforma SINTIA para que lo solucione lo antes posible.<br>
		
		<p>
			Si necesita ayuda urgente, comuniquese con el personal encargado de la plataforma y reporte los siguientes datos:<br>
			<b>ID del reporte del error:</b> <?=$idReporteError;?>.<br>
			<b>Número del error:</b> <?=$numError;?>.
			<?php if($datosUsuarioActual['uss_tipo'] == TIPO_DEV){?>
				<hr>
				<b>Detalle del error:</b> <?=$detalleError;?><br>
				<b>Linea del error:</b> <?=$lineaError;?><br>
				<b>Error trace:</b> <?=$e->getTraceAsString();?><br>
			<?php }?>
		</p>
		
		<p>
			<a href="javascript:history.go(-1);" style="color: yellow;">Regresar a la página anterior</a>
		</p>
	</div>
<?php
exit();
