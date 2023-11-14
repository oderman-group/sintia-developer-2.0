<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0132';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	$consultaIndicadoresDatos=mysqli_query($conexion, "SELECT * FROM academico_indicadores_carga 
	WHERE ipc_indicador='".base64_decode($_GET["idIndicador"])."' AND ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$indicadoresDatos = mysqli_fetch_array($consultaIndicadoresDatos, MYSQLI_BOTH);

//"Borramos" la actividad
try{
	mysqli_query($conexion, "UPDATE academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Eliminar la actividad de carga: ".$cargaConsultaActual.", del P: ".$periodoConsultaActual."' WHERE act_id=".base64_decode($_GET["idR"]));
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
//Si los valores de las calificaciones son de forma automática.

if($datosCargaActual['car_configuracion']==0){
	//Actualizamos el valor de todas las actividades del indicador
	try{
		$consultaActividadesNum=mysqli_query($conexion, "SELECT * FROM academico_actividades WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
	$actividadesNum = mysqli_num_rows($consultaActividadesNum);

	//Si hay actividades relacionadas al indicador, actualizamos su valor.
	if($actividadesNum>0){
		$valorIgualActividad = ($indicadoresDatos['ipc_valor']/($actividadesNum));
		try{
			mysqli_query($conexion, "UPDATE academico_actividades SET act_valor='".$valorIgualActividad."' WHERE act_id_tipo='".$indicadoresDatos['ipc_indicador']."' AND act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_estado=1");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}	
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'&tab=2&error=ER_DT_3";</script>';
exit();