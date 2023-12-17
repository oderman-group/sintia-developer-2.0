<?php 
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0130';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

if( $_GET["estado"] == 3 ) {

    try{
        mysqli_query($conexion, "UPDATE ".BD_GENERAL.".usuarios uss SET uss_bloqueado=0 WHERE uss_id='" . $_GET["idUsuario"] . "' AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
}

try{
    mysqli_query($conexion, "UPDATE ".BD_GENERAL.".general_solicitudes SET soli_estado='" . $_GET["estado"] . "' 
    WHERE soli_id='" . $_GET["idRegistro"] . "'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}

exit();