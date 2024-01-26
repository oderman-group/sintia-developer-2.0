<?php
	include("session.php");

	

	include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(empty($_POST["nombre"])){
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="unidades-agregar.php?error=ER_DT_4";</script>';
		exit();
	}
	require_once(ROOT_PATH."/main-app/class/Unidades.php");

	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="clases.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=2";</script>';
	exit();