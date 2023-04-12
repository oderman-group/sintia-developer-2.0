<?php
session_start();
$_SESSION['id'] = $_SESSION['acudiente'];
$_SESSION['acudiente'] = '';
unset( $_SESSION["acudiente"] );

include("../../config-general/config.php");
require_once("../class/UsuariosPadre.php");

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

header("Location:../acudiente/index.php");