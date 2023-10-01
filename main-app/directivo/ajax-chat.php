<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../compartido/sintia-funciones.php");
//Instancia de Clases generales
$usuariosClase = new Usuarios();

$id = $_REQUEST["id"];

$datosUsuarios = UsuariosPadre::sesionUsuario($id);

$fotoPerfil = $usuariosClase->verificarFoto($datosUsuarios['uss_foto']);
$nombre = UsuariosPadre::nombreCompletoDelUsuario($datosUsuarios);

$resultados[] = array(
    'datosUsuarios' => $datosUsuarios,
    'nombre'        => $nombre,
    'fotoPerfil'    => $fotoPerfil
);

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($resultados);
exit();