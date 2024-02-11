<?php
include("session.php");
require_once(ROOT_PATH . "/main-app/class/PreguntaGeneral.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0317';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

if(!empty($_POST["respuestas"])){
    $respuestasPregunta = PreguntaGeneral::traerRespuestasPreguntas($conexion, $config, $_POST['idP']);
    $idRespuestas = array();
    foreach ($respuestasPregunta as $arrayRespuesta) {
        $idRespuestas[] = $arrayRespuesta['gpr_id_respuesta'];
    }
    
    $resultadoAgregar= array_diff($_POST["respuestas"],$idRespuestas);
    if($resultadoAgregar){
        foreach ($resultadoAgregar as $idRespuestaAgregar) {
            try{
                mysqli_query($conexion,"INSERT INTO  ".BD_ADMIN.".general_preguntas_respuestas(gpr_id_pregunta, gpr_id_respuesta) VALUE('".$_POST["idP"]."', '".$idRespuestaAgregar."')");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
    }

    
    $resultadoEliminar= array_diff($idRespuestas,$_POST["respuestas"]);
    if($resultadoEliminar){
        foreach ($resultadoEliminar as $idRespuestaEliminar) {
            try{
                mysqli_query($conexion,"DELETE FROM ".BD_ADMIN.".general_preguntas_respuestas WHERE gpr_id_pregunta='".$_POST["idP"]."' AND gpr_id_respuesta='".$idRespuestaEliminar."'");
            } catch (Exception $e) {
                include("../compartido/error-catch-to-report.php");
            }
        }
    }
}else{
    try{
        mysqli_query($conexion,"DELETE FROM ".BD_ADMIN.".general_preguntas_respuestas WHERE gpr_id_pregunta='".$_POST["idP"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
}

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="preguntas.php?success=SC_DT_2&id='.base64_encode($_POST["idP"]).'";</script>';
exit();
