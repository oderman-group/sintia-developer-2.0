<?php
include("session.php");
$idPaginaInterna = 'DT0056';

Modulos::validarAccesoDirectoPaginas();
if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disciplina_reportes SET dr_aprobacion_acudiente=1, dr_aprobacion_acudiente_fecha=now() WHERE dr_id='" . base64_decode($_GET["idR"]) . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="reportes-lista.php";</script>';
exit();