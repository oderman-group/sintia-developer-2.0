<?php
	include("session.php");
	include("../modelo/conexion.php");
	
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_grados SET gra_valor_pension=0 WHERE institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();