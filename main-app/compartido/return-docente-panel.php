<?php
session_start();
$_SESSION['id'] = $_SESSION['docente'];
$_SESSION['docente'] = '';
unset( $_SESSION["docente"] );

include("../../config-general/config.php");

$consultaUsuarioAuto = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$_SESSION['id']."'");
$datosUsuarioAuto = mysqli_fetch_array($consultaUsuarioAuto, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $datosUsuarioAuto;

header("Location:../docente/index.php");