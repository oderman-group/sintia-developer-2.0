<?php
include("session.php");
require_once("../class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

	$id="";
	if(!empty($_GET["id"])){ $id=base64_decode($_GET["id"]);}

    $est =Estudiantes::obtenerDatosEstudiante($id);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	try{
		mysqli_query($conexion, "DELETE FROM ".BD_GENERAL.".usuarios WHERE uss_usuario='" . $est['mat_documento'] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	$idUsuario=Utilidades::generateCode("USS");
	try{
		mysqli_query($conexion, "INSERT INTO ".BD_GENERAL.".usuarios(uss_id, uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_nombre2, uss_apellido1, uss_apellido2, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_genero, uss_bloqueado, uss_fecha_registro, uss_responsable_registro, institucion, year)VALUES('".$idUsuario."', '" . $est['mat_documento'] . "','".$clavePorDefectoUsuarios."',4,'" . $est['mat_nombres'] . "', '" . $est['mat_nombre2'] . "', '" . $est['mat_primer_apellido'] . "', '" . $est['mat_segundo_apellido'] . "',0,'default.png','default.png',1,'blue',0,'Estudiante','notiene@gmail.com','" . $est['mat_fecha_nacimiento'] . "','" . $est['mat_genero'] . "',0,now(),'" . $_SESSION["id"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_id_usuario='" . $idUsuario . "' WHERE mat_id='" . $id . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id=' . base64_encode($idUsuario) . '";</script>';
	exit();