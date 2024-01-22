<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/PreguntaGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0284';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

if (empty($_POST["descripcion"]) or empty($_POST["tipo_pregunta"]) ) {
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="pregunta-agregar.php?error=ER_DT_4";</script>';
    exit();
}

$_POST["obligatoria"] = empty($_POST["obligatoria"]) ? 0 : 1;
$_POST["visible"] = empty($_POST["visible"]) ? 0 : 1;

$valor = PreguntaGeneral::guardar($_POST);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="pregunta-editar.php?success=SC_DT_1&id='.base64_encode("".$valor."").'";</script>';
exit();