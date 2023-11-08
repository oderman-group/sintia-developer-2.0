<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0026';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "INSERT INTO academico_clases_preguntas(cpp_usuario, cpp_fecha, cpp_id_clase, cpp_contenido)VALUES('" . $_SESSION["id"] . "', now(), '" . $_POST["idClase"] . "', '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "')");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    $datos = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM academico_clases 
    INNER JOIN academico_cargas ON car_id=cls_id_carga
    INNER JOIN usuarios ON uss_id=car_docente
    WHERE cls_id='" . $_POST["idClase"] . "' AND cls_estado=1"), MYSQLI_BOTH);
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'clases-ver.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'?idR='.base64_encode($_POST["idClase"]).'";</script>';
exit();