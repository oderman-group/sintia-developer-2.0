<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Foros.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0142';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$idR="";
if(!empty($_GET["idR"])){ $idR=base64_decode($_GET["idR"]);}

$foroC = Foros::traerComentariosForos($conexion, $config, $idR);

while($foro=mysqli_fetch_array($foroC, MYSQLI_BOTH)){
    Foros::eliminarRespuestaComentario($conexion, $config, $foro['com_id']);
}

Foros::eliminarComentarioForo($conexion, $config, $idR);

Foros::eliminarForos($conexion, $config, $idR);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="foros.php?error=ER_DT_3";</script>';
exit();