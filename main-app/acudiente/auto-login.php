<?php
include("session.php");
require_once("../class/UsuariosPadre.php");

$idPaginaInterna = 'AC0018';

$_SESSION['acudiente'] = $_SESSION['id'];

$_SESSION['id'] = base64_decode($_GET['user']);

$_SESSION["datosUsuario"] = UsuariosPadre::sesionUsuario($_SESSION['id']);

include("../compartido/guardar-historial-acciones.php");

$url = '../estudiante/index.php';

header("Location:".$url);