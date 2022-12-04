<?php
//SELECCIONAR UNA CARGA - DEBE ESTAR ARRIBA POR LAS COOKIES QUE CREA.
if($_GET["get"]==100){
	if(is_numeric($_GET["carga"]) and is_numeric($_GET["periodo"])){
		setcookie("carga",$_GET["carga"]);
		setcookie("periodo",$_GET["periodo"]);
	}
}

if(!isset($_GET["carga"]) or !isset($_GET["periodo"]) or !is_numeric($_GET["carga"]) or !is_numeric($_GET["periodo"])){
	if($_COOKIE["carga"]!="" and $_COOKIE["periodo"]!=""){
		$cargaConsultaActual = $_COOKIE["carga"];
		$periodoConsultaActual = $_COOKIE["periodo"];
	}else{
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
			exit();
	}
}else{
	$cargaConsultaActual = $_GET["carga"];
	$periodoConsultaActual = $_GET["periodo"];
}

$cargaHconsulta = mysql_fetch_array(mysql_query("SELECT * FROM academico_cargas WHERE car_id='".$cargaConsultaActual."'",$conexion));
if(mysql_errno()!=0){echo mysql_error(); exit();}
if($cargaHconsulta['car_primer_acceso_docente']==""){
	mysql_query("UPDATE academico_cargas SET car_primer_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}else{
	mysql_query("UPDATE academico_cargas SET car_ultimo_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."'",$conexion);
	if(mysql_errno()!=0){echo mysql_error(); exit();}
}

$consultaCargaActual = mysql_query("SELECT * FROM academico_cargas 
INNER JOIN academico_materias ON mat_id=car_materia
INNER JOIN academico_grados ON gra_id=car_curso
INNER JOIN academico_grupos ON gru_id=car_grupo
WHERE car_id='".$cargaConsultaActual."' AND car_docente='".$_SESSION["id"]."' AND car_activa=1",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$numCargaActual = mysql_num_rows($consultaCargaActual);
$datosCargaActual = mysql_fetch_array($consultaCargaActual);

$configCargasArray = array ("Automático","Manual"); 
$dgArray = array ("NO","SI"); 

if($numCargaActual==0)
{
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
	exit();		
}
?>