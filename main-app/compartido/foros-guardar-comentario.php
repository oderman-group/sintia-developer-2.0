<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0031';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Foros.php");
$usuariosClase = new Usuarios;

Foros::guardarComentario($conexion, $config, $_POST);

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.base64_encode($_POST["foro"]));

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();