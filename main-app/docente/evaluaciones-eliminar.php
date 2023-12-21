<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0140';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Evaluaciones.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

Evaluaciones::eliminarPreguntasEvaluacion($conexion, $config, $idR);

//Eliminamos los archivos de respuestas de las preguntas de esta evaluacion.
try{
    $rEntregas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE res_id_evaluacion='".$idR."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$rutaEntregas = ROOT_PATH."/main-app/files/evaluaciones";
while($registroEntregas = mysqli_fetch_array($rEntregas, MYSQLI_BOTH)){
    if(file_exists($ruta."/".$registro['res_archivo'])){
        unlink($ruta."/".$registro['res_archivo']);	
    }
}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_resultados WHERE res_id_evaluacion='".$idR."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$idR."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

Evaluaciones::eliminarEvaluacion($conexion, $config, $idR);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones.php?error=ER_DT_3";</script>';
exit();