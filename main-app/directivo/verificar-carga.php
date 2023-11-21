<?php
$periodo=$config['conf_periodo'];
$periodoConsultaActual=$config['conf_periodo'];
if(!isset($_GET["carga"]) or !is_numeric(base64_decode($_GET["carga"]))){
	if($_COOKIE["carga"]!="" and $_COOKIE["periodo"]!=""){
		$cargaConsultaActual = $_COOKIE["carga"];
		$periodoConsultaActual = $_COOKIE["periodo"];
	}else{
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
			exit();
	}
}else{
	if(!empty($_GET["carga"])){
		$cargaConsultaActual = base64_decode($_GET["carga"]);
	}
	if(!empty($_GET["periodo"])){
		$periodo = base64_decode($_GET["periodo"]);
		$periodoConsultaActual = base64_decode($_GET["periodo"]);
	}
}
try{
	$consultaCargaH=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_id='".$cargaConsultaActual."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
$cargaHconsulta = mysqli_fetch_array($consultaCargaH, MYSQLI_BOTH);

if($cargaHconsulta['car_primer_acceso_docente']==""){
	try{
		mysqli_query($conexion, "UPDATE academico_cargas SET car_primer_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
}else{
	try{
		mysqli_query($conexion, "UPDATE academico_cargas SET car_ultimo_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
}
//A los directivos no se les consulta el docente ni tampoco el estado de la carga (Activa o Inactiva)
try{
	$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM academico_cargas 
	INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
	INNER JOIN ".BD_ACADEMICA.".academico_grados gra ON gra_id=car_curso AND gra.institucion={$config['conf_id_institucion']} AND gra.year={$_SESSION["bd"]}
	INNER JOIN ".BD_ACADEMICA.".academico_grupos gru ON gru.gru_id=car_grupo AND gru.institucion={$config['conf_id_institucion']} AND gru.year={$_SESSION["bd"]}
	WHERE car_id='".$cargaConsultaActual."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

$numCargaActual = mysqli_num_rows($consultaCargaActual);
$datosCargaActual = mysqli_fetch_array($consultaCargaActual, MYSQLI_BOTH);

$configCargasArray = array ("Automático","Manual"); 
$dgArray = array ("NO","SI"); 

if($numCargaActual==0)
{
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
	exit();		
}
?>