<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["nombreA"])=="" or trim($_POST["posicionA"])==""){
		echo '<script type="text/javascript">window.location.href="areas-agregar.php?error=ER_DT_4";</script>';
		exit();
	}
	mysqli_query($conexion, "INSERT INTO academico_areas (ar_nombre,ar_posicion)VALUES('".$_POST["nombreA"]."',".$_POST["posicionA"].");");
	$idRegistro=mysqli_insert_id($conexion);
	
	echo '<script type="text/javascript">window.location.href="areas.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
	exit();