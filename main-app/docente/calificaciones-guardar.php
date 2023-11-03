<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0113';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	$consultaIndicadoresDatos=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
	WHERE ipc_indicador='".$_POST["indicador"]."' AND ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$indicadoresDatos = mysqli_fetch_array($consultaIndicadoresDatos, MYSQLI_BOTH);

try{
	$consultaValores=mysqli_query($conexion, "SELECT
	(SELECT sum(act_valor) FROM academico_actividades 
	WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_id_tipo='".$_POST["indicador"]."' AND act_estado=1),
	(SELECT count(*) FROM academico_actividades 
	WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1)");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$valores = mysqli_fetch_array($consultaValores, MYSQLI_BOTH);

$porcentajeRestante = $indicadoresDatos['ipc_valor'] - $valores[0];

if($valores[1]>=$datosCargaActual['car_maximas_calificaciones']){
	include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=211";</script>';
	exit();
}

$infoCompartir=0;
if(!empty($_POST["compartir"]) && $_POST["compartir"]==1) $infoCompartir=1;
$fecha = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));

if(empty($_POST["bancoDatos"]) || $_POST["bancoDatos"]==0){
	//Si los valores de las calificaciones son de forma automática.
	if($datosCargaActual['car_configuracion']==0){
		//Insertamos la calificación
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividades(act_descripcion, act_fecha, act_periodo, act_id_tipo, act_id_carga, act_estado, act_compartir, act_fecha_creacion, act_id_evidencia)"." VALUES('".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$fecha."', '".$periodoConsultaActual."','".$_POST["indicador"]."','".$cargaConsultaActual."', 1, '".$infoCompartir."', now(),'".$_POST["evidencia"]."')");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}

		//Actualizamos el valor de todas las actividades del indicador
		try{
			$consultaActividadesNum=mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
		$actividadesNum = mysqli_num_rows($consultaActividadesNum);

		//Si hay actividades relacionadas al indicador, actualizamos su valor.
		if($actividadesNum>0){
			$valorIgualActividad = ($indicadoresDatos['ipc_valor']/$actividadesNum);
			try{
				mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorIgualActividad."' WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}	
	}else{
	//Si los valores de las calificaciones son de forma manual.
		if($porcentajeRestante<=0){
			include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=212&restante='.$porcentajeRestante.'";</script>';
			exit();
		}

		if(!is_numeric($_POST["valor"])){$_POST["valor"]=1;}
		//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
		if($_POST["valor"]>$porcentajeRestante and $porcentajeRestante>0){$_POST["valor"] = $porcentajeRestante;}

		//Insertamos la calificación
		try{
			mysqli_query($conexion, "INSERT INTO academico_actividades(act_descripcion, act_fecha, act_periodo, act_id_tipo, act_id_carga, act_estado, act_compartir, act_valor, act_fecha_creacion)"." VALUES('".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$fecha."', '".$periodoConsultaActual."','".$_POST["indicador"]."','".$cargaConsultaActual."', 1, '".$infoCompartir."', '".$_POST["valor"]."', now())");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
}
//Si escoge del banco de datos
else{
}
$idRegistro=mysqli_insert_id($conexion);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="calificaciones.php?success=SC_DT_1&id='.base64_encode($idRegistro).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();