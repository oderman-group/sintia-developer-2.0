<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0030';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if (!empty($_GET["usrAct"])) {
    $usuarioActivo = $_GET["usrAct"];
} elseif (!empty($_SESSION["id"])) {
    $usuarioActivo = $_SESSION["id"];
}

if (!empty($_GET["idIns"])) {
    $idInst = $_GET["idIns"];
} else {
    $idInst = $config['conf_id_institucion'];
}


try{
    mysqli_query($conexion, "INSERT INTO " . $baseDatosServicios . ".publicidad_estadisticas(pest_publicidad, pest_institucion, pest_usuario, pest_pagina, pest_ubicacion, pest_fecha, pest_ip, pest_accion) VALUES('" . $_GET["idPub"] . "', '" . $idInst . "', '" . $usuarioActivo . "', '" . $_GET["idPag"] . "', '" . $_GET["idUb"] . "', now(), '" . $_SERVER["REMOTE_ADDR"] . "', 2)");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

if (!empty($_GET["url"])) $URL = $_GET["url"];
else $URL = $_SERVER["HTTP_REFERER"];

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $URL . '";</script>';
exit();