<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0188';
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["nombreC"])==""){
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="cursos-agregar.php?error=ER_DT_4";</script>';
		exit();
	}

	if(empty($_POST["valorM"])) {$_POST["valorM"] = '0';}
	if(empty($_POST["valorP"])) {$_POST["valorP"] = '0';}
	if(empty($_POST["graSiguiente"])) {$_POST["graSiguiente"] = 1;}
	if($_POST["tipoG"]=="")$_POST["tipoG"]= GRADO_GRUPAL;
	$codigoCurso = "GRA".strtotime("now");
	
	try{
		mysqli_query($conexion, "INSERT INTO academico_grados (gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado,gra_grado_siguiente, gra_periodos)VALUES('".$codigoCurso."', '".$_POST["nombreC"]."', '1', ".$_POST["valorM"].", ".$_POST["valorP"].", 1, '".$_POST["graSiguiente"]."', '".$config['conf_periodos_maximos']."')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	$idRegistro=mysqli_insert_id($conexion);

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
	exit();	