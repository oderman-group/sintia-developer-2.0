<?php
	include("session.php");
	include("../modelo/conexion.php");
	
	try{
		mysqli_query($conexion, "UPDATE academico_grados SET gra_formato_boletin=1");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
	exit();