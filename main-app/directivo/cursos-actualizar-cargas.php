<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0172';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

try{
	$cargas = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas WHERE car_ih!='' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	while ($c = mysqli_fetch_array($cargas, MYSQLI_BOTH)) {
		$codigo=Utilidades::generateCode("IPC");

		try{
			mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_intensidad_curso WHERE ipc_curso='" . $c['car_curso'] . "' AND ipc_materia='" . $c['car_materia'] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_intensidad_curso(ipc_id, ipc_curso, ipc_materia, ipc_intensidad, institucion, year)VALUES('".$codigo."', '" . $c['car_curso'] . "','" . $c['car_materia'] . "','" . $c['car_ih'] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
	}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();