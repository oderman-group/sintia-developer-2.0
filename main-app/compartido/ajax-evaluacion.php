<?php
session_start();
include("../../config-general/config.php");
include("../../config-general/consulta-usuario-actual.php");
require_once(ROOT_PATH."/main-app/class/Evaluaciones.php");

//CUANTOS ESTÁN REALIZANDO LA EVALUACIÓN EN ESTE MOMENTO Y CUANTOS TERMINARON
$Numerosevaluadoss = Evaluaciones::consultarEvaluados($conexion, $config, $_POST["eva"]);


if($_POST["consulta"]==1){echo $Numerosevaluadoss[0];}	
if($_POST["consulta"]==2){echo $Numerosevaluadoss[1];}	
?>