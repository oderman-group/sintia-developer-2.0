<?php
include("session.php");
$idPaginaInterna = 'DT0199';
require_once(ROOT_PATH."/main-app/class/Grupos.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreG"]) == "" || trim($_POST["codigoG"]) == "") {
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="grupos-agregar.php?error=ER_DT_4";</script>';
	exit();
}

$codigo = Grupos::guardarGrupos($conexion, $conexionPDO, $config, $_POST);

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="grupos.php?success=SC_DT_1&id=' . base64_encode($codigo) . '";</script>';
exit();
