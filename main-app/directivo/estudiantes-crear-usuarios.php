<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	$estud = mysql_query("SELECT * FROM academico_matriculas WHERE mat_eliminado=0", $conexion);
	$lineaError = __LINE__;

	include("../compartido/reporte-errores.php");

	while ($est = mysql_fetch_array($estud)) {
		$usComp = mysql_num_rows(mysql_query("SELECT * FROM usuarios WHERE uss_id='" . $est["mat_id_usuario"] . "'", $conexion));
		if ($usComp == 0) {
			mysql_query("DELETE FROM usuarios WHERE uss_usuario='" . $est[12] . "'", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}

			mysql_query("INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_foto, uss_portada, uss_idioma, uss_tema, uss_perfil, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_genero, uss_bloqueado, uss_fecha_registro, uss_responsable_registro)VALUES('" . $est[12] . "','1234',4,'" . $est[5] . " " . $est[3] . " " . $est[4] . "',0,'default.png','default.png',1,'blue',0,'Estudiante','notiene@gmail.com','" . $est[9] . "','" . $est[8] . "',0,now(),'" . $_SESSION["id"] . "')", $conexion);
			$idUsuario = mysql_insert_id();
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}

			mysql_query("UPDATE academico_matriculas SET mat_id_usuario='" . $idUsuario . "' WHERE mat_id='" . $est[0] . "'", $conexion);
			if (mysql_errno() != 0) {
				echo mysql_error();
				exit();
			}
		}
	}
	echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
	exit();