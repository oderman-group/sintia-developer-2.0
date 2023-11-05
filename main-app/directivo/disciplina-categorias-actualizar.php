<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0059';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

try{
    mysqli_query($conexion, "UPDATE ".BD_DISCIPLINA.".disciplina_categorias SET dcat_nombre='" . $_POST["categoria"] . "' WHERE dcat_id_nuevo=" . $_POST["idRNuevo"] . ";");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="disciplina-categorias-editar.php?success=SC_DT_2&idR='.base64_encode($_POST["idR"]).'&id='.base64_encode($_POST["idR"]).'";</script>';
exit();