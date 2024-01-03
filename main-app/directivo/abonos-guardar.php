<?php
	include("session.php");
	$idPaginaInterna = 'DT0266';
	require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
	require_once(ROOT_PATH."/main-app/class/Movimientos.php");

	Modulos::validarAccesoDirectoPaginas();

	if(!Modulos::validarSubRol([$idPaginaInterna])){
		require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
		exit();
	}

	$codigo=Movimientos::guardarAbonos($conexion, $config, $_POST);
	
	require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="abonos.php?success=SC_DT_1&id='.base64_encode($codigo).'";</script>';
	exit();