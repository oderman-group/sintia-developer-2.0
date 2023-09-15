<?php
	include("session.php");

	Modulos::validarAccesoDirectoPaginas();
	$idPaginaInterna = 'DC0098';

	include("verificar-carga.php");
	include("verificar-periodos-diferentes.php");

	include("../compartido/historial-acciones-guardar.php");
	try{
		mysqli_query($conexion, "UPDATE academico_unidades SET uni_eliminado=1 WHERE uni_id='".base64_decode($_GET["idR"])."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="unidades.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
	exit();