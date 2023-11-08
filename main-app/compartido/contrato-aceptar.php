<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0052';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$archivoSubido = new Archivos;
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "UPDATE academico_matriculas SET  mat_contrato=1
    WHERE mat_id_usuario='" . $_SESSION["id"] . "'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'matricula.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $url . '";</script>';
exit();