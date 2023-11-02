<?php 
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0092';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once '../class/Usuarios.php';

Usuarios::bloquearDesbloquearUsuarios($conexion,base64_decode($_GET["tipo"]),0);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="usuarios.php?tipo='.$_GET["tipo"].'";</script>';
exit();