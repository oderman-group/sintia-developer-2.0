<?php
include("session.php");
include("../modelo/conexion.php");
require_once("../class/Estudiantes.php");

if ($_POST["estadoMatricula"] == 1){

   Estudiantes::retirarRestaurarEstudiante($_POST["estudiante"], $_POST["motivo"]);
   Estudiantes::ActualizarEstadoMatricula($_POST["estudiante"], 3);

} else {
   $motivo = 'El estudiante estaba en estado '.$_POST["estadoNombre"].', pero fue restaurado exitosamente!';
   Estudiantes::retirarRestaurarEstudiante($_POST["estudiante"], $motivo);
   Estudiantes::ActualizarEstadoMatricula($_POST["estudiante"], 1);

}

echo '<script type="text/javascript">window.location.href="estudiantes-retirar.php?id='.$_POST["estudiante"].'"</script>';
exit();    