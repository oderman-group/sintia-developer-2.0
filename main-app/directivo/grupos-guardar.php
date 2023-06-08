<?php
include("session.php");
$idPaginaInterna = 'DT0195';
$accion = $_POST["accion"];
if (is_null($accion)) {
	$accion = GUARDAR;
}
include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreG"]) == "") {
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="grupos-agregar.php?error=ER_DT_4";</script>';
	exit();
}
if (trim($_POST["codigoG"]) == "") {
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="grupos-agregar.php?error=ER_DT_4";</script>';
	exit();
}

try {
	if ($accion === GUARDAR) {
		mysqli_query(
			$conexion,
			"INSERT INTO academico_grupos (
				gru_codigo, 
				gru_nombre
			)	
			VALUES(
				'" . $_POST["codigoG"] . "',
				'" . $_POST["nombreG"] . "'
			)"
		);
		$idRegistro = mysqli_insert_id($conexion);
	} else {
		mysqli_query(
			$conexion,
			"UPDATE academico_grupos SET
				gru_codigo =".$_POST['codigoG'].", 
				gru_nombre  ='".$_POST['nombreG']."'
				WHERE gru_id=".$_POST["id"].""
		);
		$idRegistro =$_POST["id"];
	}
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}


include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="grupos.php?success=SC_DT_1&id=' . $idRegistro . '";</script>';
exit();
