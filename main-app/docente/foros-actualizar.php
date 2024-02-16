<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Foros.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0125';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

Foros::actualizarForos($conexion, $config, $_POST);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="foros.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();