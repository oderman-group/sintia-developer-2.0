<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["nombreA"])=="" or trim($_POST["posicionA"])==""){
		echo '<script type="text/javascript">window.location.href="areas-editar.php?msgArea=2";</script>';
		exit();
	}
	mysqli_query($conexion, "UPDATE academico_areas SET ar_nombre='".$_POST["nombreA"]."', ar_posicion='".$_POST["posicionA"]."' WHERE ar_id='".$_POST["idA"]."'");
	
	echo '<script type="text/javascript">window.location.href="areas.php?msgArea=3";</script>';
	exit();