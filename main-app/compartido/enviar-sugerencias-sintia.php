<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0035';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

if (!empty(trim($_POST["contenido"]))) {
    try{
        mysqli_query($conexion, "INSERT INTO " . $baseDatosServicios . ".comentarios(adcom_institucion, adcom_usuario, adcom_fecha, adcom_comentario, adcom_tipo)
        VALUES('" . $config['conf_id_institucion'] . "', '" . $_SESSION["id"] . "', now(), '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "', 2)");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }

    try{
        mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios SET uss_preguntar_animo=0 WHERE uss_id='" . $_SESSION["id"] . "' AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }

}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'index.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();