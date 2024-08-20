<?php
include("session.php");
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
$idPaginaInterna = 'DT0339';

require_once("../class/Modulos.php");
Modulos::validarAccesoDirectoPaginas();

require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");
require_once(ROOT_PATH."/main-app/class/Autenticate.php");

try {
    if (empty($_GET['idInstitucion'])) {
        throw new Exception("No se encontraron los parámetros necesarios. Por favor actualice la pagina e intente nuevamente", -5);
    }

    $idInstitucion = base64_decode($_GET['idInstitucion']);

    Autenticate::getInstance()->switchInstitution($idInstitucion, $datosUsuarioActual);
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");

$url = 'index.php';

header("Location:".$url);