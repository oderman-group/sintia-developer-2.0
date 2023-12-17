<?php
include("session.php");
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
require_once("../class/UsuariosPadre.php");
Modulos::validarAccesoDirectoPaginas();

$idPaginaInterna = 'DV0073';

$_SESSION['devAdmin']      = $_SESSION['id'];
$_SESSION['admin']         = $_SESSION['id'];
$_SESSION['id']            = base64_decode($_GET['user']);
$_SESSION["idInstitucion"] = base64_decode($_GET['idInstitucion']);
$_SESSION["inst"]          = base64_decode($_GET['bd']);
$_SESSION["bd"]            = base64_decode($_GET['yearDefault']);

$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones 
WHERE ins_id='".$_SESSION["idInstitucion"]."' AND ins_enviroment='".ENVIROMENT."'");
$datosUnicosInstitucion = mysqli_fetch_array($datosUnicosInstitucionConsulta, MYSQLI_BOTH);
$_SESSION["datosUnicosInstitucion"] = $datosUnicosInstitucion;

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

$config = Plataforma::sesionConfiguracion();
$_SESSION["configuracion"] = $config;

include("../compartido/guardar-historial-acciones.php");

$url = 'index.php';

header("Location:".$url);