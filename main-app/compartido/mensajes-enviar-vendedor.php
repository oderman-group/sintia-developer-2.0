<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0041';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
$usuariosClase = new Usuarios;

try{
    $remitente = UsuariosPadre::sesionUsuario($_SESSION["id"]);
    $destinatario = UsuariosPadre::sesionUsuario($_POST["para"]);
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".social_emails(ema_de, ema_para, ema_asunto, ema_contenido, ema_fecha, ema_visto, ema_eliminado_de, ema_eliminado_para, ema_institucion, ema_year) VALUES('" . $_SESSION["id"] . "', '" . $_POST["destinoMarketplace"] . "', '" . $_POST["asuntoMarketplace"] . "', '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "', now(), 0, 0, 0,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'marketplace.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();