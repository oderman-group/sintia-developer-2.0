<?php
include("session.php");
require_once("../class/Estudiantes.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0176';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

if(empty($_POST["motivo"]))     $_POST["motivo"]    = '';

if ($_POST["estadoMatricula"] == 1){

   Estudiantes::retirarRestaurarEstudiante($_POST["estudiante"], $_POST["motivo"], $config, $_SESSION["bd"]);
   Estudiantes::ActualizarEstadoMatricula($_POST["estudiante"], 3);

} else {
   $motivo = 'El estudiante estaba en estado '.$_POST["estadoNombre"].', pero fue restaurado exitosamente!';
   Estudiantes::retirarRestaurarEstudiante($_POST["estudiante"], $motivo, $config, $_SESSION["bd"]);
   Estudiantes::ActualizarEstadoMatricula($_POST["estudiante"], 1);

}
include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="estudiantes.php?id='.base64_encode($_POST["estudiante"]).'"</script>';
exit();    