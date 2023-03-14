<?php
include("session.php");
$idPaginaInterna = 'AC0018';

$_SESSION['acudiente'] = $_SESSION['id'];

$_SESSION['id'] = $_GET['user'];

$consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$_SESSION['id']."'");
$datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $datosUsuarioAuto;

include("../compartido/guardar-historial-acciones.php");

$url = '../estudiante/index.php';

header("Location:".$url);