<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0097';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

try{
    mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disciplina_faltas SET dfal_codigo='" . $_POST["codigo"] . "', dfal_nombre='" . $_POST["nombre"] . "', dfal_id_categoria='" . $_POST["categoria"] . "' 
    WHERE dfal_id_nuevo='" . $_POST["idRNuevo"] . "'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="disciplina-faltas-editar.php?success=SC_DT_2&idR='.base64_encode($_POST["idR"]).'&id='.base64_encode($_POST["idR"]).'";</script>';
exit();