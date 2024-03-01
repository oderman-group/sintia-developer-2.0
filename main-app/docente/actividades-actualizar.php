<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0112';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Actividades.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

Actividades::actualizarActividad($conexion, $config, $_POST, $_FILES, $storage);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="actividades.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
exit();