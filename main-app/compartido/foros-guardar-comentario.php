<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0031';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("COM");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_foro_comentarios(com_id, com_id_foro, com_descripcion, com_id_estudiante, com_fecha, institucion, year)VALUES('".$codigo."', '" . mysqli_real_escape_string($conexion,$_POST["foro"]) . "', '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "', '" . $_SESSION["id"] . "', now(), {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.base64_encode($_POST["foro"]));

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();