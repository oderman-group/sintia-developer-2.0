<?php
	include("session.php");
	require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
	require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
	require_once(ROOT_PATH."/main-app/class/Grados.php");

	$filtro = " AND car_curso='".$_POST["desde"]."'";
	$numEstudiantesPromocionados = 0;
	foreach ($_POST["estudiantes"] as $idEstudiantes) {
		$consultaCargas = CargaAcademica::listarCargas($conexion, $config, $filtro, "","mat_id, car_grupo");
		while($datosCarga = mysqli_fetch_array($consultaCargas, MYSQLI_BOTH)){
			
			try {
				$consulta=mysqli_query($conexion,"UPDATE ".BD_ACADEMICA.".academico_boletin SET bol_carga='".$_POST["carga".$datosCarga['car_id']]."' WHERE bol_estudiante='".$idEstudiantes."' AND bol_carga='".$datosCarga['car_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			
			try {
				$consulta=mysqli_query($conexion,"UPDATE ".BD_ACADEMICA.".academico_nivelaciones SET niv_id_asg='".$_POST["carga".$datosCarga['car_id']]."' WHERE niv_cod_estudiante='".$idEstudiantes."' AND niv_id_asg='".$datosCarga['car_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
		}

		try {
			$consulta=mysqli_query($conexion,"UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_grado='".$_POST["para"]."', mat_grupo='".$_POST["grupo".$idEstudiantes]."', mat_promocionado=1 WHERE mat_id='".$idEstudiantes."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$numEstudiantesPromocionados++;
	}

	$consultaGradoActual=Grados::obtenerDatosGrados($_POST["desde"]);
	$gradoActual = mysqli_fetch_array($consultaGradoActual, MYSQLI_BOTH);

	$consultaGrado=Grados::obtenerDatosGrados($_POST["para"]);
	$gradoSiguiente = mysqli_fetch_array($consultaGrado, MYSQLI_BOTH);

	echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_7&curso='.base64_encode($gradoActual['gra_nombre']).'&siguiente='.base64_encode($gradoSiguiente['gra_nombre']).'&numEstudiantesPromocionados='.base64_encode($numEstudiantesPromocionados).'";</script>';
	exit();