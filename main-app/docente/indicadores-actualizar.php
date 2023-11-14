<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0128';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	$consultaSumaIndicadores=mysqli_query($conexion, "SELECT
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=0),
	(SELECT sum(ipc_valor) FROM academico_indicadores_carga 
	WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1),
	(SELECT count(*) FROM academico_indicadores_carga 
	WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND ipc_creado=1)");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$sumaIndicadores = mysqli_fetch_array($consultaSumaIndicadores, MYSQLI_BOTH);

$porcentajePermitido = 100 - $sumaIndicadores[0];
$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);
$porcentajeRestante = ($porcentajeRestante + $_POST["valorIndicador"]);

try{
	mysqli_query($conexion, "UPDATE academico_indicadores SET ind_nombre='".mysqli_real_escape_string($conexion,$_POST["contenido"])."' WHERE ind_id='".$_POST["idInd"]."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

//Si vamos a relacionar los indicadores con los SABERES
if($datosCargaActual['car_saberes_indicador']==1){
	try{
		mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_evaluacion='".$_POST["saberes"]."' WHERE ipc_id='".$_POST["idR"]."'");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

//Si los valores de los indicadores son de forma manual
if($datosCargaActual['car_valor_indicador']==1){
	if(!is_numeric($_POST["valor"])){$_POST["valor"]=1;}
	//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
	if($_POST["valor"]>$porcentajeRestante and $porcentajeRestante>0){$_POST["valor"] = $porcentajeRestante;}

	try{
		mysqli_query($conexion, "UPDATE academico_indicadores_carga SET ipc_valor='".$_POST["valor"]."' WHERE ipc_id='".$_POST["idR"]."'");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="indicadores.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();