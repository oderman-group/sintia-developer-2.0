<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0143';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
    $consultaRegistro=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases WHERE cls_id='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$registro = mysqli_fetch_array($consultaRegistro, MYSQLI_BOTH);

$ruta = ROOT_PATH."/main-app/files/clases";
if(!empty($registro['cls_archivo']) && file_exists($ruta."/".$registro['cls_archivo'])){
    unlink($ruta."/".$registro['cls_archivo']);	
}

if(!empty($registro['cls_archivo2']) && file_exists($ruta."/".$registro['cls_archivo2'])){
    unlink($ruta."/".$registro['cls_archivo2']);	
}

if(!empty($registro['cls_archivo3']) && file_exists($ruta."/".$registro['cls_archivo3'])){
    unlink($ruta."/".$registro['cls_archivo3']);	
}

try{
    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_estado=0 WHERE cls_id='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    mysqli_query($conexion, "DELETE FROM academico_ausencias WHERE aus_id_clase=".base64_decode($_GET["idR"]));
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?error=ER_DT_3";</script>';
exit();