<?php
include("../modelo/conexion.php");
//CUANTOS ESTÁN REALIZANDO LA EVALUACIÓN EN ESTE MOMENTO Y CUANTOS TERMINARON
$Numerosevaluadoss = mysql_fetch_array(mysql_query("SELECT
(SELECT count(epe_id) FROM academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$_POST["eva"]."' AND epe_fin IS NULL),
(SELECT count(epe_id) FROM academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$_POST["eva"]."' AND epe_inicio IS NOT NULL AND epe_fin IS NOT NULL)
",$conexion));
if(mysql_errno()!=0){echo mysql_error(); exit();}

if($_POST["consulta"]==1){echo $Numerosevaluadoss[0];}	
if($_POST["consulta"]==2){echo $Numerosevaluadoss[1];}	
?>