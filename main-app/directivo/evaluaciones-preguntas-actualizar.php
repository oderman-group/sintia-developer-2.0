<?php
include("session.php");
require_once(ROOT_PATH . "/main-app/class/EvaluacionGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0315';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

if(!empty($_POST["preguntas"])){
    $preguntasEvaluacion = EvaluacionGeneral::traerPreguntasEvaluacion($conexion, $config, $_POST['idE']);
    $idPreguntas = array();
    foreach ($preguntasEvaluacion as $arrayPreguntas) {
        $idPreguntas[] = $arrayPreguntas['gep_id_pregunta'];
    }
    
    $resultadoAgregar= array_diff($_POST["preguntas"],$idPreguntas);
    if($resultadoAgregar){
        foreach ($resultadoAgregar as $idPreguntaAgregar) {
            try{
                mysqli_query($conexion,"INSERT INTO  ".BD_ADMIN.".general_evaluaciones_preguntas(gep_id_evaluacion, gep_id_pregunta) VALUE('".$_POST["idE"]."', '".$idPreguntaAgregar."')");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
    }

    
    $resultadoEliminar= array_diff($idPreguntas,$_POST["preguntas"]);
    if($resultadoEliminar){
        foreach ($resultadoEliminar as $idPreguntaEliminar) {
            try{
                mysqli_query($conexion,"DELETE FROM ".BD_ADMIN.".general_evaluaciones_preguntas WHERE gep_id_evaluacion='".$_POST["idE"]."' AND gep_id_pregunta='".$idPreguntaEliminar."'");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
    }
}else{
    try{
        mysqli_query($conexion,"DELETE FROM ".BD_ADMIN.".general_evaluaciones_preguntas WHERE gep_id_evaluacion='".$_POST["idE"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="evaluaciones.php?success=SC_DT_2&id='.base64_encode($_POST["idE"]).'";</script>';
exit();
