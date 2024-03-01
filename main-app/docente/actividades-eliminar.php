<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0131';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Actividades.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

Actividades::eliminarActividadEntregas($conexion, $config, $idR, $storage);

Actividades::eliminarActividad($conexion, $config, $idR, $storage);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="actividades.php?error=ER_DT_3";</script>';
exit();