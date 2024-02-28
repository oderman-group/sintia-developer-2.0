<?php
include("session-compartida.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'CM0063';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

$config = Plataforma::sesionConfiguracion();
$_SESSION["configuracion"] = $config;

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

$arregloModulos = array();
$modulosSintia = mysqli_query($conexion, "SELECT mod_id, mod_nombre FROM ".$baseDatosServicios.".modulos
INNER JOIN ".$baseDatosServicios.".instituciones_modulos ON ipmod_institucion='".$config['conf_id_institucion']."' AND ipmod_modulo=mod_id
WHERE mod_estado=1");
while($modI = mysqli_fetch_array($modulosSintia, MYSQLI_BOTH)){
    $arregloModulos [$modI['mod_id']] = $modI['mod_nombre'];
}

$_SESSION["modulos"] = $arregloModulos;

$rst_usr = UsuariosPadre::obtenerTodosLosDatosDeUsuarios(" AND uss_id='".$_SESSION["id"]."'");
$fila = mysqli_fetch_array($rst_usr, MYSQLI_BOTH);
$_SESSION["datosUsuario"] = $fila;

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();