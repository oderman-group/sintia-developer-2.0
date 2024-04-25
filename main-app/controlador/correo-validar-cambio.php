<?php
session_start();
require_once("../../conexion.php");
if(!empty($_POST["bd"])){
	require_once(ROOT_PATH."/main-app/class/EnviarEmail.php");

	$sql="SELECT * FROM ".BD_GENERAL.".usuarios WHERE (uss_usuario='".trim($_POST["Usuario"])."-".$_POST["bd"]."' OR uss_usuario LIKE '".trim($_REQUEST["Usuario"])."%' ) AND TRIM(uss_usuario)!='' AND uss_clave=SHA1('".$_POST["Clave"]."')  AND uss_usuario IS NOT NULL  AND institucion=".$_POST["bd"]." ORDER BY uss_ultimo_ingreso DESC LIMIT 1";
	$rst_usrE = mysqli_query($conexion, $sql);
	$numE = mysqli_num_rows($rst_usrE);
	if($numE>0){
		$usrE = mysqli_fetch_array($rst_usrE, MYSQLI_BOTH);

		$data = [
			'institucion_id'   => $usrE['institucion'],
			'institucion_agno' => $usrE['year'],
			'usuario_id'       => $usrE['uss_id'],
			'usuario_email'    => $usrE['uss_email'],
			'usuario_nombre'   => $usrE["uss_nombre"]." ".$usrE["uss_apellido1"],
			'usuario_usuario'  => $usrE["uss_usuario"],
			'usuario2_email'   => 'info@oderman-group.com',
			'usuario3_email'   => 'enuarlara@oderman-group.com'
		];
		$asunto = 'Mensaje Importante: Cambio de Usuario';
		$bodyTemplateRoute = ROOT_PATH.'/config-general/plantilla-email-notificacion-cambio.php';

		EnviarEmail::enviar($data, $asunto, $bodyTemplateRoute,null,null);

		mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_cambio_notificacion=1 WHERE uss_usuario='".$usrE["uss_usuario"]."' AND uss_clave=SHA1('".$_POST["Clave"]."')  AND institucion=".$_POST["bd"]."");

		//FIN ENVÍO DE MENSAJE
		header("Location:".REDIRECT_ROUTE."/index.php?success=SC_GN_6");
		exit();
	}else{
		header("Location:".REDIRECT_ROUTE."/controlador/autentico-validar-cambio.php?error=1");
		exit();
	}
}else{
	header("Location:".REDIRECT_ROUTE."/controlador/autentico-validar-cambio.php?error=2");
	exit();
}