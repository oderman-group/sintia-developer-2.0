<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

$consultaEstudiante = Estudiantes::obtenerListadoDeEstudiantes(" AND mat_id='" . $_POST["estudiante"] . "'");

$estudiante = mysqli_fetch_array($consultaEstudiante, MYSQLI_BOTH);
try{
	$cargasConsulta = mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='" . $estudiante["mat_grado"] . "' AND car_grupo='" . $estudiante["mat_grupo"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	$contador=0;
	while ($cargasDatos = mysqli_fetch_array($cargasConsulta, MYSQLI_BOTH)) {
		try{
			$consultaCargas=mysqli_query($conexion, "SELECT * FROM academico_cargas WHERE car_curso='" . $_POST["cursoNuevo"] . "' AND car_grupo='" . $_POST["grupoNuevo"] . "' AND car_materia='" . $cargasDatos["car_materia"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$cargasConsultaNuevo = mysqli_fetch_array($consultaCargas, MYSQLI_BOTH);		
        if(!is_null($cargasConsultaNuevo)){
			try{
				mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_boletin SET bol_carga='" . $cargasConsultaNuevo["car_id"] . "' 
				WHERE bol_carga='" . $cargasDatos["car_id"] . "' AND bol_estudiante='" . $_POST["estudiante"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$contador++;
		}		
	}
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_grado='" . $_POST["cursoNuevo"] . "', mat_grupo='" . $_POST["grupoNuevo"] . "' WHERE mat_id='" . $_POST["estudiante"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	include("../compartido/guardar-historial-acciones.php");
	$msj="Se actualizaron (".$contador.") cargas para el estudiante ".Estudiantes::NombreCompletoDelEstudiante($estudiante);
	echo '<script type="text/javascript">window.location.href="estudiantes.php?success=SC_DT_4&summary='.base64_encode($msj).'&id='.base64_encode($_POST["estudiante"]).'";</script>';
	exit();