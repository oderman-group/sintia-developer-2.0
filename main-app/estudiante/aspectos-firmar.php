<?php
include("session.php");
include("verificar-usuario.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'ES0059';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "UPDATE disiplina_nota SET dn_aprobado=1, dn_fecha_aprobado=now()
	WHERE dn_cod_estudiante=" . $_POST["estudiante"] . " AND dn_periodo='" . $_POST["periodo"] . "'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="aspectos.php";</script>';
exit();