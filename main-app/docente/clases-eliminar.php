<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0143';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Clases.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

Clases::eliminarClases($conexion, $config, $idR);

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_ausencias WHERE aus_id_clase='".base64_decode($_GET["idR"])."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?error=ER_DT_3";</script>';
exit();