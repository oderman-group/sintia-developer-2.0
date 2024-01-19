<?php
	include("session.php");

	Modulos::validarAccesoDirectoPaginas();
	$idPaginaInterna = 'DT0171';

	if(!Modulos::validarSubRol([$idPaginaInterna])){
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
		exit();
	}
	include("../compartido/historial-acciones-guardar.php");

	foreach ($_POST["cargas"] as $idCarga) {
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas SET car_docente='" . $_POST["para"] . "' WHERE car_id='" . $idCarga . "' AND car_docente='" . $_POST["de"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas.php?success='.base64_encode('SC_DT_13').'";</script>';
	exit();