<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0034';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "DELETE FROM academico_actividad_foro_respuestas WHERE fore_id='" . base64_decode($_GET["idResp"]) . "'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.$_GET['idR']);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '#PUB' . $_GET["idCom"] . '";</script>';
exit();