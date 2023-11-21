<?php
	include("session.php");
	require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
	require_once(ROOT_PATH."/main-app/class/Grados.php");
	
	$consultaGrado=Grados::obtenerDatosGrados($_POST["curso"]);
	$grado = mysqli_fetch_array($consultaGrado, MYSQLI_BOTH);
	if ($grado['gra_grado_siguiente']=="") {
		echo '<script type="text/javascript">window.location.href="cursos-promocionar-estudiantes-detalles.php?curso='.base64_encode($_POST["curso"]).'&error=ER_DT_10";</script>';
		exit();
	}

	$filtro = " AND mat_grado=".$_POST['curso']." AND (mat_promocionado=0 OR mat_promocionado=NULL) AND mat_estado_matricula=1";
	$consultaEstudiantes = Estudiantes::listarEstudiantesEnGrados($filtro, '');
	$numEstudiantesPromocionados=0;
	while($datosEstudiante = mysqli_fetch_array($consultaEstudiantes, MYSQLI_BOTH)){

		if(isset($_POST["id".$datosEstudiante['mat_id']])){

			try{
				mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_grado='".$grado['gra_grado_siguiente']."', mat_promocionado=1, mat_grupo='".$_POST['grupo'.$datosEstudiante['mat_id']]."' WHERE mat_id='".$_POST["id".$datosEstudiante['mat_id']]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$numEstudiantesPromocionados++;
		}
	}

	$consultaGrado=Grados::obtenerDatosGrados($grado['gra_grado_siguiente']);
	$gradoSiguiente = mysqli_fetch_array($consultaGrado, MYSQLI_BOTH);

	echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_7&curso='.base64_encode($grado['gra_nombre']).'&siguiente='.base64_encode($gradoSiguiente['gra_nombre']).'&numEstudiantesPromocionados='.base64_encode($numEstudiantesPromocionados).'";</script>';
	exit();