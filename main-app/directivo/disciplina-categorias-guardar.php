<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0058';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

try{
    mysqli_query($conexion, "INSERT INTO ".BD_DISCIPLINA.".disciplina_categorias(dcat_nombre, dcat_institucion, dcat_year)
    VALUES('" . $_POST["categoria"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idRegistro=mysqli_insert_id($conexion);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="disciplina-categorias.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
exit();