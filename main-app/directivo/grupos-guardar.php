<?php
include("session.php");
$idPaginaInterna = 'DT0199';

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

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}


include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="grupos.php?success=SC_DT_1&id=' . $idRegistro . '";</script>';
exit();
