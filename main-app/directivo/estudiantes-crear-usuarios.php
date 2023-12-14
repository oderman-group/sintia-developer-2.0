<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0216';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

$estud = Estudiantes::obtenerListadoDeEstudiantes(" AND mat_eliminado=0");

while ($est = mysqli_fetch_array($estud, MYSQLI_BOTH)) {
	$usComp = UsuariosPadre::sesionUsuario($est["mat_id_usuario"]);
	if ( empty($usComp) ) {
		try{
			mysqli_query($conexion, "DELETE FROM ".BD_GENERAL.".usuarios WHERE uss_usuario='" . $est['mat_documento'] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		$idUsuario=Utilidades::generateCode("COM");
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_GENERAL.".usuarios(uss_id, uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_genero, uss_bloqueado, uss_fecha_registro, uss_responsable_registro, institucion, year)VALUES('".$idUsuario."', '" . $est['mat_documento'] . "','".$clavePorDefectoUsuarios."',4,'" . $est['mat_nombres'] . " " . $est['mat_primer_apellido'] . " " . $est['mat_segundo_apellido'] . "',0,'default.png','default.png',1,'blue',0,'Estudiante','notiene@gmail.com','" . $est['mat_fecha_nacimiento'] . "','" . $est['mat_genero'] . "',0,now(),'" . $_SESSION["id"] . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_matriculas SET mat_id_usuario='" . $idUsuario . "' WHERE mat_id='" . $est['mat_id'] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
}
include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();