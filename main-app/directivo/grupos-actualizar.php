<?php
include("session.php");
$idPaginaInterna = 'DT0198';

include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreG"]) == "" || trim($_POST["codigoG"]) == "") {
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="grupos-agregar.php?error=ER_DT_4";</script>';
	exit();
}

try {
	if (!is_null($_POST["id"] )) {		
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
echo '<script type="text/javascript">window.location.href="grupos.php?success=SC_DT_2&id=' . $idRegistro . '";</script>';
exit();
