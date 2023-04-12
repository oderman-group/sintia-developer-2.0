<?php
session_start();
$_SESSION['id'] = $_SESSION['admin'];
$_SESSION['admin'] = '';
unset( $_SESSION["admin"] );

include("../../config-general/config.php");
require_once("../class/UsuariosPadre.php");

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

header("Location:../directivo/usuarios.php?tipo=".$_GET['tipo']);