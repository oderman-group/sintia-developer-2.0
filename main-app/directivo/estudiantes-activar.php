<?php 
include("session.php");
include("../modelo/conexion.php");

try{
	mysqli_query($conexion, "UPDATE academico_matriculas SET mat_compromiso=0 WHERE mat_id='" . base64_decode($_GET["id"]) . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

echo '<script type="text/javascript">window.location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
exit();