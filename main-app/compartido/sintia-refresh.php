<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0063';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/UsuariosPadre.php");
require_once(ROOT_PATH."/main-app/class/RedisInstance.php");

$config = RedisInstance::getSystemConfiguration(true);

$informacionInstConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_informacion
LEFT JOIN ".$baseDatosServicios.".localidad_ciudades ON ciu_id=info_ciudad
LEFT JOIN ".$baseDatosServicios.".localidad_departamentos ON dep_id=ciu_departamento
WHERE info_institucion='" . $config['conf_id_institucion'] . "' AND info_year='" . $_SESSION["bd"] . "'");
$informacion_inst = mysqli_fetch_array($informacionInstConsulta, MYSQLI_BOTH);
$_SESSION["informacionInstConsulta"] = $informacion_inst;

$datosUnicosInstitucionConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".instituciones 
WHERE ins_id='".$config['conf_id_institucion']."' AND ins_enviroment='".ENVIROMENT."'");
$datosUnicosInstitucion = mysqli_fetch_array($datosUnicosInstitucionConsulta, MYSQLI_BOTH);
$_SESSION["datosUnicosInstitucion"] = $datosUnicosInstitucion;

$_SESSION["modulos"] = RedisInstance::getModulesInstitution(true);

$rst_usr = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_id='".$_SESSION["id"]."'");
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $fila;

$consultaSubRolesUsuario = mysqli_query($conexion, "SELECT spu_id_sub_rol 
FROM ".$baseDatosServicios.".sub_roles_usuarios 
WHERE 
    spu_id_usuario='".$_SESSION["id"]."' 
AND spu_institucion='".$config['conf_id_institucion']."'
");

$datosSubRolesUsuario = [];
$valoresPaginas       = [];

if (mysqli_num_rows($consultaSubRolesUsuario) > 0) {
    $datosSubRolesUsuario = mysqli_fetch_all($consultaSubRolesUsuario, MYSQLI_ASSOC);
    $datosSubRolesUsuario = array_column($datosSubRolesUsuario, 'spu_id_sub_rol');
    $valoresCadena        = implode(',', $datosSubRolesUsuario);

    //Consulta de paginas habilitadas para los subroles del usuario.
    $consultaPaginaSubRoles = mysqli_query($conexion, "SELECT * 
    FROM ".$baseDatosServicios.".sub_roles_paginas 
    WHERE 
        spp_id_rol IN ($valoresCadena)
    ");

    $subRolesPaginas = mysqli_fetch_all($consultaPaginaSubRoles, MYSQLI_ASSOC);
    $valoresPaginas  = array_column($subRolesPaginas, 'spp_id_pagina');
}

$_SESSION["datosUsuario"]["sub_roles"]         = $datosSubRolesUsuario;
$_SESSION["datosUsuario"]["sub_roles_paginas"] = $valoresPaginas;

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();