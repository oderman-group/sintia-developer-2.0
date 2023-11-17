<?php
include("session.php");
$idPaginaInterna = 'DT0199';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreG"]) == "" || trim($_POST["codigoG"]) == "") {
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="grupos-agregar.php?error=ER_DT_4";</script>';
	exit();
}
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("GRU");

try {
		mysqli_query(
			$conexion,
			"INSERT INTO ".BD_ACADEMICA.".academico_grupos (
				gru_id, 
				gru_codigo, 
				gru_nombre, 
				institucion, 
				year
			)	
			VALUES(
				'".$codigo."', 
				'" . $_POST["codigoG"] . "',
				'" . $_POST["nombreG"] . "', 
				{$config['conf_id_institucion']}, 
				{$_SESSION["bd"]}
			)"
		);

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}


include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="grupos.php?success=SC_DT_1&id=' . base64_encode($codigo) . '";</script>';
exit();
