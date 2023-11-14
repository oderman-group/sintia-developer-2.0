<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'AC0036';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
	mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disciplina_reportes SET dr_aprobacion_acudiente=1, dr_aprobacion_acudiente_fecha=now(), dr_comentario='".$_POST["comentario"]."' WHERE dr_id='".$_POST["id"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="estudiantes.php";</script>';
exit();