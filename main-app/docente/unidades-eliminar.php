<?php
	include("session.php");

	Modulos::validarAccesoDirectoPaginas();
	$idPaginaInterna = 'DC0098';

	include("verificar-carga.php");
	include("verificar-periodos-diferentes.php");

	include("../compartido/historial-acciones-guardar.php");
	require_once(ROOT_PATH."/main-app/class/Clases.php");

	Clases::eliminarUnidades($conexion, $config, $_GET);
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="clases.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=2";</script>';
	exit();