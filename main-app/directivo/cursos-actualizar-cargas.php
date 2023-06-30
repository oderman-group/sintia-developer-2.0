<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0172';
include("../compartido/historial-acciones-guardar.php");

try{
	$cargas = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_ih!=''");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	while ($c = mysqli_fetch_array($cargas, MYSQLI_BOTH)) {

		try{
			mysqli_query($conexion, "DELETE FROM academico_intensidad_curso WHERE ipc_curso='" . $c[2] . "' AND ipc_materia='" . $c[4] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		try{
			mysqli_query($conexion, "INSERT INTO academico_intensidad_curso(ipc_curso, ipc_materia, ipc_intensidad)VALUES('" . $c[2] . "','" . $c[4] . "','" . $c['car_ih'] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
	}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();