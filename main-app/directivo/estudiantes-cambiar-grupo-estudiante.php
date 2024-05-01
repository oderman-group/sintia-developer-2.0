<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

$consultaEstudiante = Estudiantes::obtenerListadoDeEstudiantes(" AND mat_id='" . $_POST["estudiante"] . "'");
$estudiante = mysqli_fetch_array($consultaEstudiante, MYSQLI_BOTH);

	$cargasConsulta = CargaAcademica::traerCargasMateriasPorCursoGrupo($config, $estudiante["mat_grado"], $estudiante["mat_grupo"]);
	$contador=0;
	while ($cargasDatos = mysqli_fetch_array($cargasConsulta, MYSQLI_BOTH)) {
		$cargasConsultaNuevo = CargaAcademica::traerCargasMateriasPorCursoGrupoMateria($config, $_POST["cursoNuevo"], $_POST["grupoNuevo"], $cargasDatos["car_materia"]);		
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