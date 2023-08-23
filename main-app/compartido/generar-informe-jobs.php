<?php
require_once("../../conexion.php");
require_once("../class/Sysjobs.php");
require_once("../class/Estudiantes.php");
$parametrosBuscar = array(
	"tipo" =>JOBS_TIPO_GENERAR_INFORMES,
	"estado" =>JOBS_ESTADO_PENDIENTE
);										
$listadoCrobjobs=SysJobs::listar($parametrosBuscar);


while($resultadoJobs = mysqli_fetch_array($listadoCrobjobs, MYSQLI_BOTH)){
// fecha1 es la primera fecha
$fechaInicio = new DateTime();
$finalizado = true;
$parametros = json_decode($resultadoJobs["job_parametros"], true);
$institucionId = $resultadoJobs["job_id_institucion"];
$institucionBd = $resultadoJobs["ins_bd"];
$anio = $resultadoJobs["job_year"];
$institucionBdAnio = $resultadoJobs["ins_bd"]."_".$anio;

$grado =$parametros["grado"];
$grupo =$parametros["grupo"];
$carga = $parametros["carga"];
$periodo = $parametros["periodo"];

mysqli_select_db($conexion,$institucionBdAnio);

if(empty($config)){
	$configConsulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".configuracion WHERE conf_base_datos='".$institucionBd."' AND conf_agno='".$anio."'");
	$config = mysqli_fetch_array($configConsulta, MYSQLI_BOTH);
}

//Consultamos los estudiantes del grado y grupo
$filtroAdicional= "AND mat_grado='".$grado."' AND mat_grupo='".$grupo."' AND (mat_estado_matricula=1 OR mat_estado_matricula=2)";
$consultaListaEstudante =Estudiantes::listarEstudiantesEnGrados($filtroAdicional,"");
$lineaError = __LINE__;
include("../compartido/reporte-errores.php");
	while($estudianteResultado = mysqli_fetch_array($consultaListaEstudante, MYSQLI_BOTH)){

		$estudiante = $estudianteResultado["mat_id"];
		include("../definitivas.php");

		//Consultamos si tiene registros en el boletín
		$consultaBoletinDatos=mysqli_query($conexion, "SELECT * FROM academico_boletin 
		WHERE bol_carga='".$carga."' AND bol_periodo='".$periodo."' AND bol_estudiante='".$estudiante."'");
		$boletinDatos = mysqli_fetch_array($consultaBoletinDatos, MYSQLI_BOTH); 

		//Verificamos que el estudiante tenga sus notas al 100%
		if($porcentajeActual<96 and $boletinDatos['bol_nota']==""){
			$mensaje=$estudianteResultado['mat_nombres']." ".$estudianteResultado['mat_primer_apellido']." ".$estudianteResultado['mat_segundo_apellido'] ." no tiene notas completas  id: ".$estudianteResultado['mat_id']." Valor Actual:".$porcentajeActual;
			
			$datos = array(
				"id" => $resultadoJobs['job_id'],			
				"mensaje" =>$mensaje,
				"intentos" =>$intentos
			);
			SysJobs::actualizar($datos);
			$finalizado = false;
			break;
		}
		$caso = 1; //Inserta la definitiva que viene normal 
		//Si ya existe un registro previo de definitiva TIPO 1
		if($boletinDatos['bol_id']!="" and $boletinDatos['bol_tipo']==1){
			
			if($boletinDatos['bol_nota']!=$definitiva){
				$caso = 2;//Se cambia la definitiva que tenía por la que viene. Sea menor o mayor.
			}else{
				$caso = 3;//No se hacen cambios. Todo sigue igual
				continue;
			}
			
		}
		//Si ya existe un registro previo de recuperación de periodo TIPO 2
		elseif($boletinDatos['bol_id']!="" and $boletinDatos['bol_tipo']==2){
			
			//Si la definitiva que viene está perdida 
			if($definitiva<$config[5]){
				$caso = 3;//No se hacen cambios. Todo sigue igual
				continue;
			}else{
				$caso = 4;//Se reemplaza la nota de recuperación actual por la definitiva que viene. Igual está ganada y no requiere de recuperación.
			}
			
		}
		//Si ya existe un registro previo de recuperación por Indicadores TIPO 3
		elseif($boletinDatos['bol_id']!="" and ($boletinDatos['bol_tipo']==3 or $boletinDatos['bol_tipo']==4)){
			$caso = 5;//Se actualiza la definitiva que viene y se cambia la recuperación del Indicador a nota anterior. 
	}
	//Vamos a obtener las definitivas por cada indicador y la definitiva general de la asignatura
		$notasPorIndicador = mysqli_query($conexion, "SELECT SUM((cal_nota*(act_valor/100))), act_id_tipo, ipc_valor FROM academico_calificaciones
		INNER JOIN academico_actividades ON act_id=cal_id_actividad AND act_estado=1 AND act_registrada=1 AND act_periodo='".$periodo."' AND act_id_carga='".$carga."'
		INNER JOIN academico_indicadores_carga ON ipc_indicador=act_id_tipo AND ipc_carga='".$carga."' AND ipc_periodo='".$periodo."'
		WHERE cal_id_estudiante='".$estudiante."'
		GROUP BY act_id_tipo");
		$sumaNotaIndicador = 0; 
		while($notInd = mysqli_fetch_array($notasPorIndicador, MYSQLI_BOTH)){
			$consultaNum=mysqli_query($conexion, "SELECT * FROM academico_indicadores_recuperacion 
			WHERE rind_carga='".$carga."' AND rind_estudiante='".$estudiante."' AND rind_periodo='".$periodo."' AND rind_indicador='".$notInd[1]."'");
			$num = mysqli_num_rows($consultaNum);

			
			$sumaNotaIndicador  += $notInd[0];
			
			if($num==0){
				mysqli_query($conexion, "DELETE FROM academico_indicadores_recuperacion WHERE rind_carga='".$carga."' AND rind_estudiante='".$estudiante."' AND rind_periodo='".$periodo."' AND rind_indicador='".$notInd[1]."'");
				
				
				mysqli_query($conexion, "INSERT INTO academico_indicadores_recuperacion(rind_fecha_registro, rind_estudiante, rind_carga, rind_nota, rind_indicador, rind_periodo, rind_actualizaciones, rind_nota_original, rind_nota_actual, rind_valor_indicador_registro)VALUES(now(), '".$estudiante."', '".$carga."', '".$notInd[0]."', '".$notInd[1]."', '".$periodo."', 0, '".$notInd[0]."', '".$notInd[0]."', '".$notInd[2]."')");
				
			}else{
				mysqli_query($conexion, "UPDATE academico_indicadores_recuperacion SET rind_nota_anterior=rind_nota, rind_nota='".$notInd[0]."', rind_actualizaciones=rind_actualizaciones+1, rind_ultima_actualizacion=now(), rind_nota_actual='".$notInd[0]."', rind_tipo_ultima_actualizacion=1, rind_valor_indicador_actualizacion='".$notInd[2]."' WHERE rind_carga='".$carga."' AND rind_estudiante='".$estudiante."' AND rind_periodo='".$periodo."' AND rind_indicador='".$notInd[1]."'");
				
			}
		}
		$sumaNotaIndicador = round($sumaNotaIndicador,1); 
		if($caso == 2 or $caso == 4 or $caso == 5){
			mysqli_query($conexion, "UPDATE academico_boletin SET bol_nota_anterior=bol_nota, bol_nota='".$definitiva."', bol_actualizaciones=bol_actualizaciones+1, bol_ultima_actualizacion=now(), bol_nota_indicadores='".$sumaNotaIndicador."', bol_tipo=1, bol_observaciones='Reemplazada' WHERE bol_carga='".$carga."' AND bol_periodo='".$periodo."' AND bol_estudiante='".$estudiante."'");
			
		}elseif($caso == 1){
			//Eliminamos por si acaso hay algún registro
			mysqli_query($conexion, "DELETE FROM academico_boletin 
			WHERE bol_carga='".$carga."' AND bol_periodo='".$periodo."' AND bol_estudiante='".$estudiante."'");			
			//INSERTAR LOS DATOS EN LA TABLA BOLETIN
			mysqli_query($conexion, "INSERT INTO academico_boletin(bol_carga, bol_estudiante, bol_periodo, bol_nota, bol_tipo, bol_fecha_registro, bol_actualizaciones, bol_nota_indicadores)VALUES('".$carga."', '".$estudiante."', '".$periodo."', '".$definitiva."', 1, now(), 0, '".$sumaNotaIndicador."')");	
				
		}		

	}
	if($finalizado){
		mysqli_query($conexion, "UPDATE academico_cargas SET car_periodo=car_periodo+1 WHERE car_id='".$carga."'");
		// fecha2 en este caso es la fecha actual
		$fechaFinal = new DateTime();
		$tiempoTrasncurrido=minutosTranscurridos($fechaInicio,$fechaFinal); 
		$datos = array(
			"id" => $resultadoJobs['job_id'],
			"mensaje" => "Cron job ejecutado Exitosamente, ".$tiempoTrasncurrido."!",
			"estado" =>JOBS_ESTADO_FINALIZADO,
		);
		SysJobs::actualizar($datos);
	}
	

}



function minutosTranscurridos($fecha_i,$fecha_f)
{
$intervalo = $fecha_i->diff($fecha_f);
$minutos = $intervalo->i;
$segundos = $intervalo->s;
return " Finalizo en: $minutos Min y $segundos Seg.";
}

exit()
?>