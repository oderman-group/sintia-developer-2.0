<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
$_SESSION['id']       = $_SESSION['devAdmin'];
$_SESSION['admin']    = '';
$_SESSION['devAdmin'] = '';

$idInstitucion = DEVELOPER;
if(ENVIROMENT == 'PROD') {
    $idInstitucion = DEVELOPER_PROD;
}

$_SESSION["idInstitucion"] = $idInstitucion;
$_SESSION["inst"]          = 'dev';
$_SESSION["bd"]            = date("Y");

unset( $_SESSION["admin"] );
unset( $_SESSION["devAdmin"] );

include("../../config-general/config.php");
require_once("../class/UsuariosPadre.php");

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones 
WHERE ins_id='".$idInstitucion."' AND ins_enviroment='".ENVIROMENT."'");
$datosUnicosInstitucion = mysqli_fetch_array($datosUnicosInstitucionConsulta, MYSQLI_BOTH);
$_SESSION["datosUnicosInstitucion"] = $datosUnicosInstitucion;

header("Location:../directivo/dev-instituciones.php");