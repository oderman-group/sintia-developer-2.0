<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0026';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Clases.php");
$codigo=Utilidades::generateCode("CPP");
$usuariosClase = new Usuarios;

Clases::guardarPreguntasClases($conexion, $config, $_POST);

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'clases-ver.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'?idR='.base64_encode($_POST["idClase"]).'";</script>';
exit();