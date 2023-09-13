<?php
session_start();
include("../../config-general/config.php");
require_once("../class/Sysjobs.php");
Modulos::validarAccesoDirectoPaginas();
$parametros = array(
    "carga" =>$_GET['carga'],
    "periodo" => $_GET['periodo'],
    "grado" => $_GET['grado'],
	"grupo"=>$_GET['grupo']
);
try{
    //HISTORIAL DE ACCIONES
	$mensaje=SysJobs::registrar(JOBS_TIPO_GENERAR_INFORMES,JOBS_PRIORIDAD_BAJA,$parametros);	
	include("../compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="../docente/cargas.php";</script>';
	exit();

} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}