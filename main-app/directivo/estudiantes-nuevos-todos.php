<?php 
include("session.php");

try{
	mysqli_query($conexion, "UPDATE academico_matriculas SET mat_tipo=128");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();