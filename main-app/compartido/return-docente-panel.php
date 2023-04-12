<?php
session_start();
$_SESSION['id'] = $_SESSION['docente'];
$_SESSION['docente'] = '';
unset( $_SESSION["docente"] );

include("../../config-general/config.php");
require_once("../class/UsuariosPadre.php");

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

header("Location:../docente/index.php");