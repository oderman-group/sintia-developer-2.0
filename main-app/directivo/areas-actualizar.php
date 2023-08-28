<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0165';

if(!Modulos::validarSubRol($idPaginaInterna)){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if(trim($_POST["nombreA"])=="" or trim($_POST["posicionA"])==""){
	echo '<script type="text/javascript">window.location.href="areas-editar.php?error=ER_DT_4";</script>';
	exit();
}

try{
	mysqli_query($conexion, "UPDATE academico_areas SET ar_nombre='".$_POST["nombreA"]."', ar_posicion='".$_POST["posicionA"]."' WHERE ar_id='".$_POST["idA"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="areas.php?success=SC_DT_2&id='.$_POST["idA"].'";</script>';
	exit();