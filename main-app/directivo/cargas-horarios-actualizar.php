<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0169';
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["idH"]) == "" or trim($_POST["inicioH"]) == "" or trim($_POST["finH"]) == "") {
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}

try{
	mysqli_query($conexion, "UPDATE academico_horarios SET hor_dia=" . $_POST["diaH"] . ", hor_desde='" . $_POST["inicioH"] . "', hor_hasta='" . $_POST["finH"] . "' WHERE hor_id=" . $_POST["idH"] . ";");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="cargas-horarios.php?id=' . $_POST["idC"] . '";</script>';
	exit();