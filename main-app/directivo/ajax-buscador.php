<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
include("../compartido/sintia-funciones.php");
//Instancia de Clases generales
$usuariosClase = new Usuarios();

$busqueda = $_REQUEST["search"];
$filtro = " AND (
    uss_nombre LIKE '%".$busqueda."%' 
    OR uss_nombre2 LIKE '%".$busqueda."%' 
    OR uss_apellido1 LIKE '%".$busqueda."%' 
    OR uss_apellido2 LIKE '%".$busqueda."%' 
    OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_nombre2), ' ', TRIM(uss_apellido1), ' ', TRIM(uss_apellido2)) LIKE '%".$busqueda."%'
    OR CONCAT(TRIM(uss_apellido1), ' ', TRIM(uss_apellido2), ' ', TRIM(uss_nombre), ' ', TRIM(uss_nombre2)) LIKE '%".$busqueda."%'
    OR CONCAT(TRIM(uss_nombre), ' ', TRIM(uss_apellido1)) LIKE '%".$busqueda."%'
    OR CONCAT(TRIM(uss_apellido1), ' ', TRIM(uss_nombre)) LIKE '%".$busqueda."%' 
)";

$consultaUsuarios = mysqli_query($conexion,"SELECT uss_id, uss_nombre, uss_apellido1, uss_foto, uss_estado FROM ".BD_GENERAL.".usuarios WHERE uss_bloqueado=0 AND uss_id!='".$_SESSION['id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} $filtro LIMIT 20");

$resultados = array();

while ($datosUsuarios = mysqli_fetch_array($consultaUsuarios, MYSQLI_BOTH)) {
    $fotoPerfil = $usuariosClase->verificarFoto($datosUsuarios['uss_foto']);

    $nombre = $datosUsuarios['uss_nombre'];
    if ($datosUsuarios['uss_apellido1'] != "" || $datosUsuarios['uss_apellido1'] != NULL) {
        $nombre .= " ".$datosUsuarios['uss_apellido1'];
    }

    $resultados[] = array(
        'datosUsuarios' => $datosUsuarios,
        'nombre'        => $nombre,
        'fotoPerfil'    => $fotoPerfil
    );
}

// Devolver los resultados como JSON
header('Content-Type: application/json');
echo json_encode($resultados);
exit();