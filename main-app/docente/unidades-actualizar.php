<?php
	include("session.php");

	Modulos::validarAccesoDirectoPaginas();
	$idPaginaInterna = 'DC0097';

	include("verificar-carga.php");
	include("verificar-periodos-diferentes.php");

	include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(empty($_POST["nombre"])){
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="unidades-editar.php?error=ER_DT_4";</script>';
		exit();
	}
	require_once(ROOT_PATH."/main-app/class/Clases.php");

	Clases::actualizarUnidades($conexion, $config, $cargaConsultaActual, $periodoConsultaActual, $_POST);
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="clases.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=2";</script>';
	exit();