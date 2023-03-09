<?php
	include("session.php");
	require_once(ROOT_PATH."/main-app/class/Grados.php");
	
	$consultaGrado=Grados::obtenerDatosGrados($_POST["curso"]);
	$grado = mysqli_fetch_array($consultaGrado, MYSQLI_BOTH);
	if ($grado['gra_grado_siguiente']=="") {
		echo '<script type="text/javascript">window.location.href="cursos-promocionar-estudiantes-filtros.php?curso='.$_POST["curso"].'&error=ER_DT_10";</script>';
		exit();
	}

	$numEstudiantes = (count($_POST["estudiantes"]));
	$contEstudiantes = 0;
	while ($contEstudiantes < $numEstudiantes) {

		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_grado=".$grado['gra_grado_siguiente'].", mat_promocionado=1 WHERE mat_id=".$_POST['estudiantes'][$contEstudiantes]."");

		if($_POST["grupo"]!=""){
			mysqli_query($conexion, "UPDATE academico_matriculas SET mat_grupo=".$_POST['grupo']." WHERE mat_id=".$_POST['estudiantes'][$contEstudiantes]."");
		}
		$contEstudiantes++;
	}

	echo '<script type="text/javascript">window.location.href="cursos.php?curso='.$_POST["curso"].'&success=SC_DT_7";</script>';
	exit();