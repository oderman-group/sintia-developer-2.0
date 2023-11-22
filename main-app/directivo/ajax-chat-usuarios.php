<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../compartido/sintia-funciones.php");
//Instancia de Clases generales
$usuariosClase = new Usuarios();


$consultaUsuariosOnline = mysqli_query($conexion,"SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM ".BD_GENERAL.".usuarios WHERE uss_estado=1 AND uss_bloqueado=0 AND uss_id!='".$_SESSION['id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} LIMIT 10");
$consultaUsuariosOfline = mysqli_query($conexion,"SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM ".BD_GENERAL.".usuarios WHERE uss_estado=0 AND uss_bloqueado=0 AND uss_id!='".$_SESSION['id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} LIMIT 5");

$resultadosOnline = array();
$resultadosOfline = array();

while ($datosUsuarios = mysqli_fetch_array($consultaUsuariosOnline, MYSQLI_BOTH)) {
    $fotoPerfil = $usuariosClase->verificarFoto($datosUsuarios['uss_foto']);

    $nombre = $datosUsuarios['uss_nombre'];
    if ($datosUsuarios['uss_apellido1'] != "" || $datosUsuarios['uss_apellido1'] != NULL) {
        $nombre .= " ".$datosUsuarios['uss_apellido1'];
    }

    $resultadosOnline[] = array(
        'datosUsuarios' => $datosUsuarios,
        'nombre'        => $nombre,
        'fotoPerfil'    => $fotoPerfil
    );
}
while ($datosUsuarios = mysqli_fetch_array($consultaUsuariosOfline, MYSQLI_BOTH)) {
    $fotoPerfil = $usuariosClase->verificarFoto($datosUsuarios['uss_foto']);

    $nombre = $datosUsuarios['uss_nombre'];
    if ($datosUsuarios['uss_apellido1'] != "" || $datosUsuarios['uss_apellido1'] != NULL) {
        $nombre .= " ".$datosUsuarios['uss_apellido1'];
    }

    $resultadosOfline[] = array(
        'datosUsuarios' => $datosUsuarios,
        'nombre'        => $nombre,
        'fotoPerfil'    => $fotoPerfil
    );
}

$resultados = array_merge($resultadosOnline, $resultadosOfline);
// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($resultados);
exit();