<?php
if($datosUsuarioActual[3]==4){
	$usuarioEstudianteConsultaActual = $_SESSION["id"];
}else{
	if(is_numeric($_GET["usrEstud"])){
		$usuarioEstudianteConsultaActual = $_GET["usrEstud"];
	}else{
		//Redireccionamos
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=300";</script>';
		exit();
	}
}

//ESTUDIANTE ACTUAL
$consultaEstudianteActual = mysql_query("SELECT * FROM academico_matriculas
LEFT JOIN usuarios ON uss_id=mat_acudiente
INNER JOIN academico_grados ON gra_id=mat_grado
WHERE mat_id_usuario='".$usuarioEstudianteConsultaActual."'",$conexion);
if(mysql_errno()!=0){echo mysql_error(); exit();}
$numEstudianteActual = mysql_num_rows($consultaEstudianteActual);
$datosEstudianteActual = mysql_fetch_array($consultaEstudianteActual);
?>