<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0127';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Indicadores.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");
$idRegistro=Utilidades::generateCode("IND");

$sumaIndicadores = Indicadores::consultarSumaIndicadores($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);
$porcentajePermitido = 100 - $sumaIndicadores[0];
$porcentajeRestante = ($porcentajePermitido - $sumaIndicadores[1]);

if($sumaIndicadores[2]>=$datosCargaActual['car_maximos_indicadores']){
	include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=209";</script>';
	exit();
}

$infoCompartir=0;
if(!empty($_POST["compartir"]) && $_POST["compartir"]==1) $infoCompartir=1;
if(empty($_POST["bancoDatos"]) || $_POST["bancoDatos"]==0){
	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores(ind_id, ind_nombre, ind_obligatorio, ind_publico, institucion, year) VALUES('".$idRegistro."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."', 0, '".$infoCompartir."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if($datosCargaActual['car_valor_indicador']==1){
		if($porcentajeRestante<=0){
			include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=210&restante='.$porcentajeRestante.'";</script>';
			exit();
		}

		if(!is_numeric($_POST["valor"])){$_POST["valor"]=1;}
		//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
		if($_POST["valor"]>$porcentajeRestante and $porcentajeRestante>0){$_POST["valor"] = $porcentajeRestante;}

		Indicadores::guardarIndicadorCarga($conexion, $config, $cargaConsultaActual, $idRegistro, $periodoConsultaActual, $_POST, NULL);
	}else{
		//El sistema reparte los porcentajes automáticamente y equitativamente.
		$valorIgualIndicador = ($porcentajePermitido/($sumaIndicadores[2]+1));

		Indicadores::guardarIndicadorCarga($conexion, $config, $cargaConsultaActual, $idRegistro, $periodoConsultaActual, $_POST, NULL);

		//Actualiza todos valores de la misma carga y periodo; incluyendo el que acaba de crear.
		Indicadores::actualizarValorIndicadores($conexion, $config, $cargaConsultaActual, $periodoConsultaActual, $valorIgualIndicador);
	}
}else{
//Si escoge del banco de datos
	try{
		$consultaIndicadorBD=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores ai
		INNER JOIN ".BD_ACADEMICA.".academico_indicadores_carga ipc ON ipc.ipc_indicador=ai.ind_id AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]}
		WHERE ai.ind_id='".$_POST["bancoDatos"]."' AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
	$indicadorBD = mysqli_fetch_array($consultaIndicadorBD, MYSQLI_BOTH);

	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores(ind_id, ind_nombre, ind_obligatorio, ind_publico, institucion, year) VALUES('".$idRegistro."', '".$indicadorBD['ind_nombre']."', 0, 1, {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
	//Si decide poner los valores porcentuales de los indicadores de forma manual
	if($datosCargaActual['car_valor_indicador']==1){
		if($porcentajeRestante<=0){
			include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=210&restante='.$porcentajeRestante.'";</script>';
			exit();
		}
		//Si el valor es mayor al adecuado lo ajustamos al porcentaje restante; Siempre que este último sea mayor a 0.
		if($indicadorBD['ipc_valor']>$porcentajeRestante and $porcentajeRestante>0){$indicadorBD['ipc_valor'] = $porcentajeRestante;}

		Indicadores::guardarIndicadorCarga($conexion, $config, $cargaConsultaActual, $idRegistro, $periodoConsultaActual, NULL, $indicadorBD);
	}else{
	//El sistema reparte los porcentajes automáticamente y equitativamente.
		$valorIgualIndicador = ($porcentajePermitido/($sumaIndicadores[2]+1));

		Indicadores::guardarIndicadorCarga($conexion, $config, $cargaConsultaActual, $idRegistro, $periodoConsultaActual, NULL, $indicadorBD);

		//Actualiza todos valores de la misma carga y periodo; incluyendo el que acaba de crear.
		Indicadores::actualizarValorIndicadores($conexion, $config, $cargaConsultaActual, $periodoConsultaActual, $valorIgualIndicador);
	}
}
//Si las calificaciones son de forma automática.
if($datosCargaActual['car_configuracion']==0){
	Calificaciones::actualizarValorCalificacionesDeUnaCarga($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);			
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="indicadores.php?success=SC_DT_1&id='.base64_encode($idRegistro).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();