<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");

$estud = Estudiantes::obtenerListadoDeEstudiantes(" AND mat_eliminado=0");

while ($est = mysqli_fetch_array($estud, MYSQLI_BOTH)) {
	$usComp = UsuariosPadre::sesionUsuario($est["mat_id_usuario"]);
	if ( empty($usComp) ) {
		try{
			mysqli_query($conexion, "DELETE FROM usuarios WHERE uss_usuario='" . $est[12] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		try{
			mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_genero, uss_bloqueado, uss_fecha_registro, uss_responsable_registro)VALUES('" . $est[12] . "','".$clavePorDefectoUsuarios."',4,'" . $est[5] . " " . $est[3] . " " . $est[4] . "',0,'default.png','default.png',1,'blue',0,'Estudiante','notiene@gmail.com','" . $est[9] . "','" . $est[8] . "',0,now(),'" . $_SESSION["id"] . "')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$idUsuario = mysqli_insert_id($conexion);
		
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_id_usuario='" . $idUsuario . "' WHERE mat_id='" . $est[0] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
}
echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();