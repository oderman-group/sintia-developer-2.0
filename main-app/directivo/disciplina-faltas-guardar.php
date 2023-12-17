<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0095';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

$idRegistro=Utilidades::generateCode("FL");
try{
    mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disciplina_faltas(dfal_id, dfal_nombre, dfal_id_categoria, dfal_codigo, dfal_institucion, dfal_year)
    VALUES('" .$idRegistro . "', '" . $_POST["nombre"] . "', '" . $_POST["categoria"] . "', '" . $_POST["codigo"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="disciplina-faltas.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
exit();