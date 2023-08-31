<?php
include("session.php");

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
		echo '<script type="text/javascript">window.location.href="areas-agregar.php?error=ER_DT_4";</script>';
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO academico_areas (ar_nombre,ar_posicion)VALUES('".$_POST["nombreA"]."',".$_POST["posicionA"].");");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="areas.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
	exit();