<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0033';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "INSERT INTO academico_actividad_foro_respuestas(fore_id_estudiante, fore_id_comentario, fore_fecha, fore_respuesta)VALUES('" . $_SESSION["id"] . "', '" . $_POST["comentario"] . "', now(), '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "')");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'foros-detalles.php?idR='.base64_encode($_POST["idR"]));

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '#PUB' . base64_encode($_POST["comentario"]) . '";</script>';
exit();