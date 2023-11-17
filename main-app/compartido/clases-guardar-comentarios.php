<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0026';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("CPP");
$usuariosClase = new Usuarios;

try{
    mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_clases_preguntas(cpp_id, cpp_usuario, cpp_fecha, cpp_id_clase, cpp_contenido, institucion, year)VALUES('".$codigo."', '" . $_SESSION["id"] . "', now(), '" . $_POST["idClase"] . "', '" . mysqli_real_escape_string($conexion,$_POST["contenido"]) . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    $datos = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases cls
    INNER JOIN academico_cargas ON car_id=cls.cls_id_carga
    INNER JOIN usuarios ON uss_id=car_docente
    WHERE cls.cls_id='" . $_POST["idClase"] . "' AND cls.cls_estado=1 AND cls.institucion={$config['conf_id_institucion']} AND cls.year={$_SESSION["bd"]}"), MYSQLI_BOTH);
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'clases-ver.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'?idR='.base64_encode($_POST["idClase"]).'";</script>';
exit();