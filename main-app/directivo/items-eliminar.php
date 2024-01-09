<?php
	include("session.php");
	$idPaginaInterna = 'DT0263';
	require_once(ROOT_PATH."/main-app/class/Movimientos.php");
	require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

	Modulos::validarAccesoDirectoPaginas();

	if(!Modulos::validarSubRol([$idPaginaInterna])){
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
		exit();
	}

	$id = '';
	if (!empty($_GET['id'])) {
		$id = base64_decode($_GET['id']);
	}

	Movimientos::eliminarItems($conexion, $config, $id);

	require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="items.php?error=ER_DT_3";</script>';
	exit();