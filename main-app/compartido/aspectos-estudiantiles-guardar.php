<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0016';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".matriculas_aspectos(mata_estudiante, mata_usuario, mata_fecha_evento, mata_aspectos_positivos, mata_aspectos_mejorar, mata_tratamiento, mata_descripcion, mata_periodo, institucion, year)VALUES('" . $_POST["estudiante"] . "', '" . $_SESSION['id'] . "', '" . $_POST["fecha"] . "', '" . $_POST["positivos"] . "', '" . $_POST["mejorar"] . "', '" . $_POST["tratamiento"] . "', '" . $_POST["descripcion"] . "', '" . $_POST["periodo"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'aspectos-estudiantiles.php?idR='.$_POST["idR"]);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
exit();