<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0139';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

if(base64_decode($_GET["estado"])==0) $estado=1; else $estado=0;
try{
    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_respuestas SET resp_correcta='".$estado."' WHERE resp_id='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-preguntas.php?success=SC_GN_5&idE='.$_GET["idE"].'&estado='.base64_encode($estado).'#pregunta'.base64_encode($_GET["preg"]).'";</script>';
exit();