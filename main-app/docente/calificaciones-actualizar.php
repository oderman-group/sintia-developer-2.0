<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0114';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	$consultaIndicadoresDatosC=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga 
	WHERE ipc_indicador='".$_POST["indicador"]."' AND ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$indicadoresDatosC = mysqli_fetch_array($consultaIndicadoresDatosC, MYSQLI_BOTH);

try{
	$consultaValores=mysqli_query($conexion, "SELECT
	(SELECT sum(act_valor) FROM ".BD_ACADEMICA.".academico_actividades 
	WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND act_id_tipo='".$_POST["indicador"]."' AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$valores = mysqli_fetch_array($consultaValores, MYSQLI_BOTH);

$porcentajeRestante = $indicadoresDatosC['ipc_valor'] - $valores[0];
$porcentajeRestante = ($porcentajeRestante + $_POST["valorCalificacion"]);

$fecha = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));

//Si las calificaciones son de forma automática.
if($datosCargaActual['car_configuracion']==0){
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_descripcion='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', act_fecha='".$fecha."', act_id_tipo='".$_POST["indicador"]."', act_fecha_modificacion=now(), act_id_evidencia='".$_POST["evidencia"]."' 
		WHERE act_id='".$_POST["idR"]."'  AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	//Actualizamos los valores de todas las actividades de la carga
	Calificaciones::actualizarValorCalificacionesDeUnaCarga($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);

}else{
//Si las calificaciones son de forma manual.
	if($porcentajeRestante<=0){
		include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=212&restante='.$porcentajeRestante.'";</script>';
		exit();
	}

	if(!is_numeric($_POST["valor"])){$_POST["valor"]=1;}
	//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
	if($_POST["valor"]>$porcentajeRestante and $porcentajeRestante>0){$_POST["valor"] = $porcentajeRestante;}

	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_descripcion='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', act_fecha='".$fecha."', act_id_tipo='".$_POST["indicador"]."', act_valor='".$_POST["valor"]."', act_fecha_modificacion=now() 
		WHERE act_id='".$_POST["idR"]."'  AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="calificaciones.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
exit();