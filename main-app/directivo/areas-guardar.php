<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0179';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["nombreA"])=="" or trim($_POST["posicionA"])==""){
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="areas.php?error=ER_DT_4";</script>';
		exit();
	}

	$codigo=Utilidades::generateCode("AR");
	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_areas (ar_id, ar_nombre,ar_posicion, institucion, year)VALUES('".$codigo."', '".$_POST["nombreA"]."',".$_POST["posicionA"].", {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="areas.php?success=SC_DT_1&id='.base64_encode($codigo).'";</script>';
	exit();