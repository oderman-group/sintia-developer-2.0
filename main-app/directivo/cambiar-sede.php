<?php
include("session.php");
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once("../class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");
require_once(ROOT_PATH."/main-app/class/Autenticate.php");
Modulos::validarAccesoDirectoPaginas();

$idPaginaInterna = 'DT0339';

$idInstitucion = base64_decode($_GET['idInstitucion']);

try {
    Autenticate::getInstance()->switchInstitution($idInstitucion);
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

$url = 'index.php';

header("Location:".$url);