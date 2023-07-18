<?php
//SELECCIONAR UNA CARGA - DEBE ESTAR ARRIBA POR LAS COOKIES QUE CREA.
if(isset($_GET["get"]) && $_GET["get"]==100){
	if(is_numeric($_GET["carga"]) and is_numeric($_GET["periodo"])){
		setcookie("carga",$_GET["carga"]);
		setcookie("periodo",$_GET["periodo"]);
		
		$infoCargaActual = [];

		$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM academico_cargas 
		INNER JOIN academico_materias ON mat_id=car_materia
		INNER JOIN academico_grados ON gra_id=car_curso
		INNER JOIN academico_grupos ON gru_id=car_grupo
		WHERE car_id='".$_GET["carga"]."' AND car_docente='".$_SESSION["id"]."' AND car_activa=1");

		$datosCargaActual = mysqli_fetch_array($consultaCargaActual, MYSQLI_BOTH);

		$infoCargaActual = [
			'datosCargaActual'  => $datosCargaActual
		];

		$_SESSION["infoCargaActual"] = $infoCargaActual;
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


/*$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM academico_cargas 
INNER JOIN academico_materias ON mat_id=car_materia
INNER JOIN academico_grados ON gra_id=car_curso
INNER JOIN academico_grupos ON gru_id=car_grupo
WHERE car_id='".$cargaConsultaActual."' AND car_docente='".$_SESSION["id"]."' AND car_activa=1");

$numCargaActual = mysqli_num_rows($consultaCargaActual);*/
$datosCargaActual = $_SESSION["infoCargaActual"]['datosCargaActual'];

if($datosCargaActual['car_primer_acceso_docente']==""){
	mysqli_query($conexion, "UPDATE academico_cargas SET car_primer_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."'");
	
}else{
	mysqli_query($conexion, "UPDATE academico_cargas SET car_ultimo_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."'");
	
}

$configCargasArray = array ("Automático","Manual"); 
$dgArray = array ("NO","SI"); 

if(empty($datosCargaActual))
{
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
	exit();		
}
$filtroDocentesParaListarEstudiantes = " AND mat_grado='".$datosCargaActual['car_curso']."' AND mat_grupo='".$datosCargaActual['car_grupo']."'";
