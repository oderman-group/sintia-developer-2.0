<?php
	include("session.php");

	Modulos::validarAccesoDirectoPaginas();
	$idPaginaInterna = 'DC0095';

	include("verificar-carga.php");
	include("verificar-periodos-diferentes.php");

	include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(empty($_POST["nombre"])){
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="unidades-agregar.php?error=ER_DT_4";</script>';
		exit();
	}
	require_once(ROOT_PATH."/main-app/class/Utilidades.php");
	$codigo=Utilidades::generateCode("UNI");
	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_unidades (uni_id, uni_nombre,uni_id_carga,uni_periodo,uni_descripcion, institucion, year)VALUES('".$codigo."', '".$_POST["nombre"]."','".$cargaConsultaActual."','".$periodoConsultaActual."','".$_POST["contenido"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="clases.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=2";</script>';
	exit();