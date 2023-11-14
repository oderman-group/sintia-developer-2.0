<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0022';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    $reaccion = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".social_noticias_reacciones WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . base64_decode($_GET["idR"]) . "'"), MYSQLI_BOTH);
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
if (empty($reaccion[0])) {
    try{
        mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".social_noticias_reacciones(npr_usuario, npr_noticia, npr_reaccion, npr_fecha, npr_estado, npr_institucion, npr_year)VALUES('" . $_SESSION["id"] . "', '" . base64_decode($_GET["idR"]) . "','" . base64_decode($_GET["r"]) . "',now(),1,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
} else {
    try{
        mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".social_noticias_reacciones SET npr_reaccion='" . base64_decode($_GET["r"]) . "' WHERE npr_usuario='" . $_SESSION["id"] . "' AND npr_noticia='" . base64_decode($_GET["idR"]) . "'");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
}

try{
    mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_alertas (alr_nombre, alr_descripcion, alr_tipo, alr_usuario, alr_fecha_envio, alr_categoria, alr_importancia, alr_vista, alr_institucion, alr_year)
    VALUES('<b>" . base64_decode($_GET["usrname"]) . "</b> ha reaccionado a tu publicación', '<b>" . base64_decode($_GET["usrname"]) . "</b> ha reaccionado a tu publicación " . base64_decode($_GET["postname"]) . ".', 2, '" . base64_decode($_GET["postowner"]) . "', now(), 3, 2, 0,'" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idNotify = mysqli_insert_id($conexion);
try{
    mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".general_alertas SET alr_url_acceso='noticias.php?idNotify=" . $idNotify . "#PUB" . $_GET["idR"] . "' WHERE alr_id='" . $idNotify . "'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'noticias.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'#PUB' . $_GET["idR"] . '";</script>';
exit();