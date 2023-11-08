<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0020';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias SET not_estado='" . base64_decode($_GET["e"]) . "' WHERE not_usuario='" . $_SESSION["id"] . "' AND not_estado!=2");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'noticias.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
exit();