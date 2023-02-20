<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["nombreC"])==""){
		echo '<script type="text/javascript">window.location.href="cursos-agregar.php?error=ER_DT_4";</script>';
		exit();
	}

	if(empty($_POST["valorM"])) {$_POST["valorM"] = '0';}
	if(empty($_POST["valorP"])) {$_POST["valorP"] = '0';}
	if(empty($_POST["graSiguiente"])) {$_POST["graSiguiente"] = 1;}
	$codigoCurso = "GRA".strtotime("now");
	
	mysqli_query($conexion, "INSERT INTO academico_grados (gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado,gra_grado_siguiente)VALUES('".$codigoCurso."', '".$_POST["nombreC"]."', '1', ".$_POST["valorM"].", ".$_POST["valorP"].", 1, '".$_POST["graSiguiente"]."')");
	$idRegistro=mysqli_insert_id($conexion);

	echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
	exit();	