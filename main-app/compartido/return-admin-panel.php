<?php
session_start();
$_SESSION['id'] = $_SESSION['admin'];
$_SESSION['admin'] = '';
unset( $_SESSION["admin"] );

include("../../config-general/config.php");

$consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$_SESSION['id']."'");
$datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $datosUsuarioAuto;

header("Location:../directivo/usuarios.php?tipo=".$_GET['tipo']);