<?php
	include("session.php");
	$idPaginaInterna = 'DT0262';
	require_once(ROOT_PATH."/main-app/class/Movimientos.php");
	require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

	Modulos::validarAccesoDirectoPaginas();

	if(!Modulos::validarSubRol([$idPaginaInterna])){
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
		exit();
	}

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(empty($_POST["nombre"])){
		echo '<script type="text/javascript">window.location.href="items-editar.php?error=ER_DT_4&id='.base64_encode($_POST["id"]).'";</script>';
		exit();
	}

	Movimientos::actualizarItems($conexion, $config, $_POST);

	require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="items.php?success=SC_DT_2&id='.base64_encode($_POST["id"]).'";</script>';
	exit();