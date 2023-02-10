<?php 
include("session.php"); 
include("../modelo/conexion.php"); 

	mysqli_query($conexion, "DELETE FROM academico_materias WHERE mat_id=".$_GET["id"].";");
	
	echo '<script type="text/javascript">window.location.href="asignaturas.php?error=ER_DT_3";</script>';
	exit();