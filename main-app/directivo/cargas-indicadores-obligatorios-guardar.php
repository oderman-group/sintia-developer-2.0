<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0185';
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombre"]) == "" or trim($_POST["valor"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	
	try{
		$consultaInd=mysqli_query($conexion, "SELECT sum(ind_valor)+" . $_POST["valor"] . " FROM academico_indicadores where ind_obligatorio=1");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$ind = mysqli_fetch_array($consultaInd, MYSQLI_BOTH);

	if ($ind[0] > 100) {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Los valores de los indicadores no deben superar el 100%.</samp>";
		exit();
	}

	try{
		mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_valor, ind_obligatorio)VALUES('" . $_POST["nombre"] . "','" . $_POST["valor"] . "',1)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();