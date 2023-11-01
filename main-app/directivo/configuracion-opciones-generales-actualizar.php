<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
Modulos::verificarPermisoDev();

$idPaginaInterna = 'DV0072';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try{
    mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".opciones_generales SET ogen_nombre='" . $_POST["nombre"] . "', ogen_grupo='" . $_POST["grupo"] . "' WHERE ogen_id='" . $_POST["idogen"] . "'");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="configuracion-opciones-generales.php"</script>';
exit();