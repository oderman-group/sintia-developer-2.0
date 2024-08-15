<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0034';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Foros.php");
$usuariosClase = new UsuariosFunciones;

$idResp="";
if(!empty($_GET["idResp"])){ $idResp=base64_decode($_GET["idResp"]);}

Foros::eliminarRespuesta($conexion, $config, $idResp);

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.$_GET['idR']);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '#PUB' . $_GET["idCom"] . '";</script>';
exit();