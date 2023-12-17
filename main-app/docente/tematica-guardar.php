<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0129';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

try{
	$consultaNumTema=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores 
	WHERE ind_periodo='".$periodoConsultaActual."' AND ind_carga='".$cargaConsultaActual."' AND ind_tematica=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$numTema = mysqli_num_rows($consultaNumTema);



if($numTema>0){

	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_indicadores SET ind_nombre='".$_POST["contenido"]."', ind_fecha_modificacion=now() WHERE ind_periodo='".$periodoConsultaActual."' AND ind_carga='".$cargaConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}else{
	$codigo=Utilidades::generateCode("IND");

	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores(ind_id, ind_nombre, ind_obligatorio, ind_periodo, ind_carga, ind_fecha_creacion, ind_tematica, institucion, year) VALUES('".$codigo."', '".$_POST["contenido"]."', 0, '".$periodoConsultaActual."', '".$cargaConsultaActual."', now(), 1, {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?success=SC_GN_3&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=4";</script>';
exit();