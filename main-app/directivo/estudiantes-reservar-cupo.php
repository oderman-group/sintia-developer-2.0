<?php
include("session.php");
include("../modelo/conexion.php");

	mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".general_encuestas(genc_estudiante, genc_fecha, genc_respuesta, genc_comentario, genc_institucion, genc_year) VALUES('".$_GET["idEstudiante"]."', now(), 1, 'Reservado por un directivo.','" . $config['conf_id_institucion'] . "','" . $_SESSION["bd"] . "')");
	
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();