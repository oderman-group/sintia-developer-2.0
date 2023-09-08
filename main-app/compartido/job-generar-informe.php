<?php
session_start();
include("../../config-general/config.php");
require_once("../class/Sysjobs.php");
Modulos::validarAccesoDirectoPaginas();
$parametros = array(
    "carga" =>base64_decode($_GET['carga']),
    "periodo" => base64_decode($_GET['periodo']),
    "grado" => base64_decode($_GET['grado']),
	"grupo"=> base64_decode($_GET['grupo'])
);
try{
    //HISTORIAL DE ACCIONES
	$mensaje=SysJobs::registrar(JOBS_TIPO_GENERAR_INFORMES,$parametros);	
	include("../compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="../docente/cargas.php?success=SC_DT_4&summary=' . $mensaje.'";</script>';
	exit();

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}