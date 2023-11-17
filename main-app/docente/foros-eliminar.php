<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0142';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
    $foroC = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE com_id_foro='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

while($foro=mysqli_fetch_array($foroC, MYSQLI_BOTH)){
    try{
        mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE fore_id_comentario='".$foro[0]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_comentarios WHERE com_id_foro='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro WHERE foro_id='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="foros.php?error=ER_DT_3";</script>';
exit();