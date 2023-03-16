<?php
include("session.php");
include("../modelo/conexion.php");
include("../class/Estudiantes.php");

if ($_POST["estadoMatricula"] == 1){

   Estudiantes::retirarRestaurarEstudiante($_POST["estudiante"], $_POST["motivo"]);
   Estudiantes::ActualizarEstadoMatricula($_POST["estudiante"], 3);

} elseif ($_POST["estadoMatricula"] == 3){
   $motivo = 'El Estudiante Estaba Retirado, Pero Fue Restaurado Exitosamente!';
   Estudiantes::retirarRestaurarEstudiante($_POST["estudiante"], $motivo);
   Estudiantes::ActualizarEstadoMatricula($_POST["estudiante"], 1);

}

echo '<script type="text/javascript">window.location.href="estudiantes-retirar.php?id='.$_POST["estudiante"].'"</script>';
exit();    