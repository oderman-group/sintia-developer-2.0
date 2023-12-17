<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
//CUANTOS ESTÁN REALIZANDO LA EVALUACIÓN EN ESTE MOMENTO Y CUANTOS TERMINARON
$consultaNumerosEvaluadoss=mysqli_query($conexion, "SELECT
(SELECT count(epe_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$_POST["eva"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND epe_fin IS NULL),
(SELECT count(epe_id) FROM ".BD_ACADEMICA.".academico_actividad_evaluaciones_estudiantes WHERE epe_id_evaluacion='".$_POST["eva"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND epe_inicio IS NOT NULL AND epe_fin IS NOT NULL)");
$Numerosevaluadoss = mysqli_fetch_array($consultaNumerosEvaluadoss, MYSQLI_BOTH);


if($_POST["consulta"]==1){echo $Numerosevaluadoss[0];}	
if($_POST["consulta"]==2){echo $Numerosevaluadoss[1];}	
?>