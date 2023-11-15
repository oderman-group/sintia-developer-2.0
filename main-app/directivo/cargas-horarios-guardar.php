<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0184';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["idH"]) == "" or trim($_POST["inicioH"]) == "" or trim($_POST["finH"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

	$numero = (count($_POST["diaH"]));
	$contador = 0;
	while ($contador < $numero) {
		$codigo = "HOR".$_POST["diaH"][$contador].strtotime("now");
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_horarios(hor_id, hor_id_carga, hor_dia, hor_desde, hor_hasta, institucion, year)VALUES('" . $codigo . "'," . $_POST["idH"] . ",'" . $_POST["diaH"][$contador] . "','" . $_POST["inicioH"] . "','" . $_POST["finH"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$contador++;
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . base64_encode($_POST["idH"]) . '";</script>';
	exit();