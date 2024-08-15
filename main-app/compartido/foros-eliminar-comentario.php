<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0032';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Foros.php");
$usuariosClase = new UsuariosFunciones;

$idCom="";
if(!empty($_GET["idCom"])){ $idCom=base64_decode($_GET["idCom"]);}

Foros::eliminarRespuestaComentario($conexion, $config, $idCom);

Foros::eliminarComentario($conexion, $config, $idCom);

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.$_GET['idR']);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();