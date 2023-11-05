<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0129';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	$consultaNumTema=mysqli_query($conexion, "SELECT * FROM academico_indicadores 
	WHERE ind_periodo='".$periodoConsultaActual."' AND ind_carga='".$cargaConsultaActual."' AND ind_tematica=1");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$numTema = mysqli_num_rows($consultaNumTema);



if($numTema>0){

	try{
		mysqli_query($conexion, "UPDATE academico_indicadores SET ind_nombre='".$_POST["contenido"]."', ind_fecha_modificacion=now() WHERE ind_periodo='".$periodoConsultaActual."' AND ind_carga='".$cargaConsultaActual."'");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}else{

	try{
		mysqli_query($conexion, "INSERT INTO academico_indicadores(ind_nombre, ind_obligatorio, ind_periodo, ind_carga, ind_fecha_creacion, ind_tematica) VALUES('".$_POST["contenido"]."', 0, '".$periodoConsultaActual."', '".$cargaConsultaActual."', now(), 1)");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?success=SC_GN_3&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=4";</script>';
exit();