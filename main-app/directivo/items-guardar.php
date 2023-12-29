<?php
	include("session.php");
	$idPaginaInterna = 'DT0260';
	require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
	require_once(ROOT_PATH."/main-app/class/Movimientos.php");

	Modulos::validarAccesoDirectoPaginas();

	if(!Modulos::validarSubRol([$idPaginaInterna])){
		require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
		exit();
	}

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(empty($_POST["nombre"])){
		require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="items-agregar.php?error=ER_DT_4";</script>';
		exit();
	}

	$codigo=Movimientos::guardarItems($conexion, $config, $_POST);
	
	require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="items.php?success=SC_DT_1&id='.base64_encode($codigo).'";</script>';
	exit();