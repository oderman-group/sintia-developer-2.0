<?php include("session.php");?>
<?php
if(isset($_POST["id"])){
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_ip, hil_so)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Acciones POST - ".$_SERVER['HTTP_REFERER']."', now(), '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER['HTTP_USER_AGENT']."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}	
}elseif(isset($_GET["get"])){
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_ip, hil_so)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Acciones GET - ".$_SERVER['HTTP_REFERER']."', now(), '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER['HTTP_USER_AGENT']."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}	
}else{
	mysql_query("INSERT INTO seguridad_historial_acciones(hil_usuario, hil_url, hil_titulo, hil_fecha, hil_ip, hil_so)VALUES('".$_SESSION["id"]."', '".$_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']."', 'Acciones DESCONOCIDA - ".$_SERVER['HTTP_REFERER']."', now(), '".$_SERVER["REMOTE_ADDR"]."', '".$_SERVER['HTTP_USER_AGENT']."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}
?>

<?php
//SOLICITUD DE DESBLOQUEO
if($_POST["id"]==1){	
	mysql_query("INSERT INTO general_solicitudes(soli_id_recurso, soli_remitente, soli_fecha, soli_mensaje, soli_estado, soli_tipo)
	VALUES('".$_POST["idRecurso"]."', '".$_SESSION["id"]."', now(), '".$_POST["contenido"]."', 1, 1)",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=110";</script>';
	exit();
}
//ENCUESTA RESERVA DE CUPO
if($_POST["id"]==2){	
	mysql_query("INSERT INTO general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario)
	VALUES('".$_POST["idEstudiante"]."', now(), '".$_POST["respuesta"]."', '".$_POST["motivo"]."')",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=111";</script>';
	exit();
}
//FIRMAR ASPECTOS
if($_POST["id"]==3){

	mysql_query("UPDATE disiplina_nota SET dn_aprobado=1, dn_fecha_aprobado=now()
    WHERE dn_cod_estudiante=" . $_POST["estudiante"] . " AND dn_periodo='" . $_POST["periodo"] . "'", $conexion);

	if(mysql_errno()!=0){echo mysql_error(); exit();}

	echo '<script type="text/javascript">window.location.href="'.$_SERVER["HTTP_REFERER"].'";</script>';

	exit();

}

//========================================== GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET GET  GET GET GET GET GET GET GET GET GET GET GET GET GET ======================================================
//FIRMA DIGITAL DE LOS REPORTES
if($_GET["get"]==1){
	mysql_query("UPDATE disciplina_reportes SET dr_aprobacion_acudiente=1, dr_aprobacion_acudiente_fecha=now(), dr_comentario='".$_GET["comentario"]."' WHERE dr_id='".$_GET["id"]."'",$conexion);
	$lineaError = __LINE__;
	include("../compartido/reporte-errores.php");
	echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
	exit();
}
?>