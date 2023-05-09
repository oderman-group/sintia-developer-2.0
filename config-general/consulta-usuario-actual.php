<?php
if(!isset($idSession) || $idSession==""){$idSession = $_SESSION["id"];}
$datosUsuarioActual = $_SESSION["datosUsuario"];

//SE RECARGA VARIABLE SESSION PARA EL USUARIO ACTUAL
if(isset($_SESSION["yearAnterior"])){
    $consultaUss = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_id='".$datosUsuarioActual['uss_id']."'");
    $datosUsuarioActual = mysqli_fetch_array($consultaUss, MYSQLI_BOTH);
}