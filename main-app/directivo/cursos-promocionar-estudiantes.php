<?php
	include("session.php");
	require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
	require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
	require_once(ROOT_PATH."/main-app/class/Grados.php");
    require_once(ROOT_PATH."/main-app/class/Calificaciones.php");

	$filtro = " AND car_curso='".$_POST["desde"]."'";
	$numEstudiantesPromocionados = 0;
	foreach ($_POST["estudiantes"] as $idEstudiantes) {
		$cambiarEstado = !empty($_POST["estado".$idEstudiantes]) ? ", mat_estado_matricula=1" : "";
		$grupo = (!empty($_POST["grupoPara"]) && $_POST["grupoPara"] != 0)  ? $_POST["grupoPara"] : $_POST["grupo".$idEstudiantes];

		try {
			$consulta=mysqli_query($conexion,"UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_grado='".$_POST["para"]."', mat_grupo='".$grupo."', mat_promocionado=1 {$cambiarEstado} WHERE mat_id='".$idEstudiantes."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}

		if (!empty($_POST['relacionCargas']) || $_POST['relacionCargas'] == 1) {
			$filtro .= (!empty($_POST["grupoDesde"]) && $_POST["grupoDesde"] != 0) ? " AND car_grupo='".$_POST["grupoDesde"]."'" : "";
			$consultaCargas = CargaAcademica::listarCargas($conexion, $config, "", $filtro,"mat_id, car_grupo");
			while($datosCarga = mysqli_fetch_array($consultaCargas, MYSQLI_BOTH)){
				
				try {
					$consulta=mysqli_query($conexion,"UPDATE ".BD_ACADEMICA.".academico_boletin SET bol_carga='".$_POST["carga".$datosCarga['car_id']]."' WHERE bol_estudiante='".$idEstudiantes."' AND bol_carga='".$datosCarga['car_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
				} catch (Exception $e) {
					include("../compartido/error-catch-to-report.php");
				}
				
				Calificaciones::transferirNivelacion($conexion, $config, $_POST["carga".$datosCarga['car_id']], $datosCarga['car_id'], $idEstudiantes);
			}
		}
		$numEstudiantesPromocionados++;
	}

	$consultaGradoActual=Grados::obtenerDatosGrados($_POST["desde"]);
	$gradoActual = mysqli_fetch_array($consultaGradoActual, MYSQLI_BOTH);

	$consultaGrado=Grados::obtenerDatosGrados($_POST["para"]);
	$gradoSiguiente = mysqli_fetch_array($consultaGrado, MYSQLI_BOTH);

	echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_7&curso='.base64_encode($gradoActual['gra_nombre']).'&siguiente='.base64_encode($gradoSiguiente['gra_nombre']).'&numEstudiantesPromocionados='.base64_encode($numEstudiantesPromocionados).'";</script>';
	exit();