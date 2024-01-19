<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/EvaluacionGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0284';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

if (empty($_POST["fecha"]) or empty($_POST["nombre"]) or empty($_POST["clave"])) {
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="evaluacion-agregar.php?error=ER_DT_4";</script>';
    exit();
}

$valor=EvaluacionGeneral::guardar($_POST);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="evaluacion-editar.php?success=SC_DT_1&id='.base64_encode("".$valor."").'";</script>';
exit();