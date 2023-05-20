<?php 
include("session.php");
include("verificar-carga.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'DC0092';
include("../compartido/historial-acciones-guardar.php");

try{
    $consultaNum = mysqli_query($conexion, "SELECT academico_calificaciones.cal_id_actividad, academico_calificaciones.cal_id_estudiante FROM academico_calificaciones 
    WHERE academico_calificaciones.cal_id_actividad='".$_POST["codNota"]."' AND academico_calificaciones.cal_id_estudiante='".$_POST["codEst"]."'");
} catch (Exception $e) {
    include("../compartido/error-catch-to-report.php");
}
$num = mysqli_num_rows($consultaNum);

$mensajeNot = 'Hubo un error al guardar las cambios';

if(trim($_POST["nota"])==""){echo "<span style='color:red; font-size:16px;'>Digite una nota correcta</span>";exit();}
if($_POST["nota"]>$config[4]) $_POST["nota"] = $config[4]; if($_POST["nota"]<$config[3]) $_POST["nota"] = $config[3];

if($num==0){
    try{
        mysqli_query($conexion, "DELETE FROM academico_calificaciones WHERE cal_id_actividad='".$_POST["codNota"]."' AND cal_id_estudiante='".$_POST["codEst"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    
    try{
        mysqli_query($conexion, "INSERT INTO academico_calificaciones(cal_id_estudiante, cal_nota, cal_id_actividad, cal_fecha_registrada, cal_cantidad_modificaciones)VALUES('".$_POST["codEst"]."','".$_POST["nota"]."','".$_POST["codNota"]."', now(), 0)");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    
    try{
        mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1, act_fecha_registro=now() WHERE act_id='".$_POST["codNota"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }

    $mensajeNot = 'La nota se ha guardado correctamente para el estudiante <b>'.strtoupper($_POST["nombreEst"]).'</b>';

    //Si la institución autoriza el envío de mensajes - Requiere datos relacionados de unas consultas que fueron eliminadas
    //include("calificaciones-enviar-email.php");

}else{
    if($_POST["notaAnterior"]==""){$_POST["notaAnterior"] = "0.0";}
    
    try{
        mysqli_query($conexion, "UPDATE academico_calificaciones SET cal_nota='".$_POST["nota"]."', cal_fecha_modificada=now(), cal_cantidad_modificaciones=cal_cantidad_modificaciones+1, cal_nota_anterior='".$_POST["notaAnterior"]."', cal_tipo=1 
        WHERE cal_id_actividad='".$_POST["codNota"]."' AND cal_id_estudiante='".$_POST["codEst"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    
    try{
        mysqli_query($conexion, "UPDATE academico_actividades SET act_registrada=1 WHERE act_id='".$_POST["codNota"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }

    $mensajeNot = 'La nota se ha actualizado correctamente para el estudiante <b>'.strtoupper($_POST["nombreEst"]).'</b>';

}

include("../compartido/guardar-historial-acciones.php");
?>

<script type="text/javascript">
function notifica(){
	$.toast({
		heading: 'Cambios guardados',  
		text: '<?=$mensajeNot;?>',
		position: 'botom-left',
		loaderBg:'#ff6849',
		icon: 'success',
		hideAfter: 3000, 
		stack: 6
	});
}
setTimeout ("notifica()", 100);
</script>

<div class="alert alert-success">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<i class="icon-exclamation-sign"></i><strong>INFORMACI&Oacute;N:</strong> <?=$mensajeNot;?>
</div>
