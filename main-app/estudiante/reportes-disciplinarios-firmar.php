<?php
include("session.php");
include("verificar-usuario.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'ES0060';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

$id=base64_decode($_GET["id"]);
try{
	mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disciplina_reportes SET dr_aprobacion_estudiante=1, dr_aprobacion_estudiante_fecha=now() WHERE dr_id='".$id."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="reportes-disciplinarios.php";</script>';
exit();