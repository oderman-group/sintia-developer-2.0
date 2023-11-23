<?php
$carga="";
if(!empty($_GET["carga"])){ $carga=base64_decode($_GET["carga"]);}
$periodo="";
if(!empty($_GET["periodo"])){ $periodo=base64_decode($_GET["periodo"]);}

//SELECCIONAR UNA CARGA - DEBE ESTAR ARRIBA POR LAS COOKIES QUE CREA.
if(isset($_GET["get"]) && base64_decode($_GET["get"])==100){
	if(is_numeric($carga) and is_numeric($periodo)){
		setcookie("carga",$carga);
		setcookie("periodo",$periodo);
		require_once("../class/CargaAcademica.php");

		$infoCargaActual = CargaAcademica::cargasDatosEnSesion($carga, $_SESSION["id"]);
		$_SESSION["infoCargaActual"] = $infoCargaActual;
	}
}

if(!isset($_GET["carga"]) or !isset($_GET["periodo"]) or !is_numeric($carga) or !is_numeric($periodo)){
	if($_COOKIE["carga"]!="" and $_COOKIE["periodo"]!=""){
		$cargaConsultaActual = $_COOKIE["carga"];
		$periodoConsultaActual = $_COOKIE["periodo"];
	}else{
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
			exit();
	}
}else{
	$cargaConsultaActual = $carga;
	$periodoConsultaActual = $periodo;
}

if( !empty($_SESSION["infoCargaActual"]) ) {
	$datosCargaActual = $_SESSION["infoCargaActual"]['datosCargaActual'];

	if(!empty($datosCargaActual['car_primer_acceso_docente']) && $datosCargaActual['car_primer_acceso_docente']==""){
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas SET car_primer_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		
	}else{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas SET car_ultimo_acceso_docente=now() WHERE car_id='".$cargaConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		
	}
}

$configCargasArray = array ("Automático","Manual"); 
$dgArray = array ("NO","SI"); 

if(empty($datosCargaActual))
{
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
	exit();		
}
$filtroDocentesParaListarEstudiantes = " AND mat_grado='".$datosCargaActual['car_curso']."' AND mat_grupo='".$datosCargaActual['car_grupo']."'";

require_once("../class/Estudiantes.php");
if($datosCargaActual['gra_tipo'] == GRADO_INDIVIDUAL) {
	$cantidadEstudiantesParaDocentes = Estudiantes::contarEstudiantesParaDocentesMT($datosCargaActual);
} else {
	$cantidadEstudiantesParaDocentes = Estudiantes::contarEstudiantesParaDocentes($filtroDocentesParaListarEstudiantes);
}
