<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0032';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Foros.php");
$usuariosClase = new Usuarios;

$idCom="";
if(!empty($_GET["idCom"])){ $idCom=base64_decode($_GET["idCom"]);}

try{
    mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_foro_respuestas WHERE fore_id_comentario='" . base64_decode($_GET["idCom"]) . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

Foros::eliminarComentario($conexion, $config, $idCom);

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.$_GET['idR']);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();