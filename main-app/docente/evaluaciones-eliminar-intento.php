<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0141';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_estudiantes 
    WHERE epe_id_evaluacion='".base64_decode($_GET["idE"])."' AND epe_id_estudiante='".base64_decode($_GET["idEstudiante"])."'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_evaluaciones_resultados 
    WHERE res_id_evaluacion='".base64_decode($_GET["idE"])."' AND res_id_estudiante='".base64_decode($_GET["idEstudiante"])."'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-resultados.php?error=ER_DT_3&idE='.$_GET["idE"].'";</script>';
exit();