<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0188';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codGRADO=Utilidades::generateCode("GRAD");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["nombreC"])==""){
		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="cursos.php?error=ER_DT_4";</script>';
		exit();
	}

	if(empty($_POST["valorM"])) {$_POST["valorM"] = '0';}
	if(empty($_POST["valorP"])) {$_POST["valorP"] = '0';}
	if(empty($_POST["graSiguiente"])) {$_POST["graSiguiente"] = 1;}
	if(empty($_POST["tipoG"])){ $_POST["tipoG"]=GRADO_GRUPAL;}
	$codigoCurso = "GRA".strtotime("now");
	

		if(empty($_POST["imagen"])) {$_POST["imagen"] = '';}
		if(empty($_POST["descripcion"])) {$_POST["descripcion"] = '';}
		if(empty($_POST["contenido"])) {$_POST["contenido"] = '';}
		if(empty($_POST["precio"])) {$_POST["precio"] = '0';}
		if(empty($_POST["minEstudiantes"])) {$_POST["minEstudiantes"] = '0';}
		if(empty($_POST["maxEstudiantes"])) {$_POST["maxEstudiantes"] = '0';}
		if(empty($_POST["horas"])) {$_POST["horas"] = '0';}
		if(empty($_POST["autoenrollment"])) {$_POST["autoenrollment"] = '0';}
		if(empty($_POST["activo"])) {$_POST["activo"] = '0';}
		$_POST["autoenrollment"] = empty($_POST["autoenrollment"]) ? 0 : 1;
		$_POST["activo"] = empty($_POST["activo"]) ? 0 : 1;

	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_grados (gra_id, gra_codigo, gra_nombre, gra_formato_boletin, gra_valor_matricula, gra_valor_pension, gra_estado,gra_grado_siguiente, gra_periodos, gra_tipo, institucion, year)VALUES('".$codGRADO."', '".$codigoCurso."', '".$_POST["nombreC"]."', '1', ".$_POST["valorM"].", ".$_POST["valorP"].", 1, '".$_POST["graSiguiente"]."', '".$config['conf_periodos_maximos']."', '".$_POST["tipoG"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cursos.php?success=SC_DT_1&id='.base64_encode($codGRADO).'";</script>';
	exit();	