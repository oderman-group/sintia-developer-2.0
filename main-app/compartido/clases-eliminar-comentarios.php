<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0015';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "DELETE FROM academico_clases_preguntas WHERE cpp_id='" . base64_decode($_GET["idCom"]) . "'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'clases-ver.php?idR='.$_GET["idR"]);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
exit();