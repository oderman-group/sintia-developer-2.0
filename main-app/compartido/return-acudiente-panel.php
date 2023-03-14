<?php
session_start();
$_SESSION['id'] = $_SESSION['acudiente'];
$_SESSION['acudiente'] = '';
unset( $_SESSION["acudiente"] );

include("../../config-general/config.php");

$consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$_SESSION['id']."'");
$datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $datosUsuarioAuto;

header("Location:../acudiente/index.php");