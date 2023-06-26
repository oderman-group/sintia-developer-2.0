<?php
include("session.php");

	//SE CREA MATRICULA EN AÑO SIGUIENTE
	try{
		mysqli_query($conexion, "INSERT INTO ".$bdApasar.".academico_matriculas SELECT * FROM ".$bdActual.".academico_matriculas WHERE mat_id='".$_GET["matricula"]."' ");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "UPDATE ".$bdApasar.".academico_matriculas SET mat_estado_matricula=4, mat_grupo=1 WHERE mat_id='".$_GET["matricula"]."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//CONSULTAMOS DATOS DEL ESTUDIANTE
	try{
		$consultaMatricula=mysqli_query($conexion, "SELECT * FROM ".$bdActual.".academico_matriculas WHERE mat_id='".$_GET["matricula"]."' ");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$datosMatricula = mysqli_fetch_array($consultaMatricula, MYSQLI_BOTH);

	//SE CREA EL USUARIO DEL ESTUDIANTE
	try{
		mysqli_query($conexion, "INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_id_usuario"]."' ");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//SE CREA EL USUARIO DEL ACUDIENTE
	try{
		mysqli_query($conexion, "INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_acudiente"]."' ");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//SE CREA EL USUARIO DEL PADRE
	try{
		mysqli_query($conexion, "INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_padre"]."' ");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//SE CREA EL USUARIO DE LA MADRE
	try{
		mysqli_query($conexion, "INSERT INTO ".$bdApasar.".usuarios SELECT * FROM ".$bdActual.".usuarios WHERE uss_id='".$datosMatricula["mat_madre"]."' ");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//SE CREA RELACION ENTRE ACUDIENTE Y ESTUDIANTE
	try{
		mysqli_query($conexion, "INSERT INTO ".$bdApasar.".usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$datosMatricula["mat_acudiente"]."', '".$datosMatricula["mat_id_usuario"]."')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	//SE ACTUALIZA EL ESTADO DEL ASPIRANTE
	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosAdmisiones.".aspirantes SET asp_estado_solicitud=9 WHERE asp_id='".$datosMatricula["mat_solicitud_inscripcion"]."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	echo '<script type="text/javascript">window.location.href="inscripciones.php";</script>';
	exit();