<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
Modulos::verificarPermisoDev();

$idPaginaInterna = 'DV0070';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".opciones_generales (ogen_nombre, ogen_grupo)VALUES('" . $_POST["nombre"] . "','" . $_POST["grupo"] . "')");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idIte = mysqli_insert_id($conexion);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php"</script>';
exit();