<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0026';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Clases.php");
$codigo=Utilidades::generateCode("CPP");
$usuariosClase = new Usuarios;

Clases::guardarPreguntasClases($conexion, $config, $_POST);

try{
    $datos = mysqli_fetch_array(mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_clases cls
    INNER JOIN ".BD_ACADEMICA.".academico_cargas car ON car_id=cls.cls_id_carga AND car.institucion={$config['conf_id_institucion']} AND car.year={$_SESSION["bd"]}
    INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
    WHERE cls.cls_id='" . $_POST["idClase"] . "' AND cls.cls_estado=1 AND cls.institucion={$config['conf_id_institucion']} AND cls.year={$_SESSION["bd"]}"), MYSQLI_BOTH);
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$url= $usuariosClase->verificarTipoUsuario($datosUsuarioActual['uss_tipo'],'clases-ver.php');

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$url.'?idR='.base64_encode($_POST["idClase"]).'";</script>';
exit();