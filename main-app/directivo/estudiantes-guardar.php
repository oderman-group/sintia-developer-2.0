<?php
include("session.php");
require_once("../class/Estudiantes.php");
require_once("../class/servicios/MediaTecnicaServicios.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0192';
include("../compartido/historial-acciones-guardar.php");

$_POST["ciudadR"] = trim($_POST["ciudadR"]);


//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if(trim($_POST["nDoc"])=="" or trim($_POST["apellido1"])=="" or trim($_POST["nombres"])=="" or trim($_POST["grado"])=="" or trim($_POST["documentoA"])==""){

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes-agregar.php?error=ER_DT_4&tipoD='.$_POST["tipoD"].'&documento='.$_POST["nDoc"].'&religion='.$_POST["religion"].'&email='.$_POST["email"].'&direcion='.$_POST["direccion"].'&barrio='.$_POST["barrio"].'&telefono='.$_POST["telefono"].'&celular='.$_POST["celular"].'&estrato='.$_POST["estrato"].'&genero='.$_POST["genero"].'&nacimiento='.$_POST["fNac"].'&apellido1='.$_POST["apellido1"].'&apellido2='.$_POST["apellido2"].'&nombre='.$_POST["nombres"].'&grado='.$_POST["grado"].'&grupo='.$_POST["grupo"].'&tipoE='.$_POST["tipoEst"].'&lugarEx='.$_POST["lugarD"].'&lugarNac='.$_POST["lNac"].'&matricula='.$_POST["matricula"].'&folio='.$_POST["folio"].'&tesoreria='.$_POST["codTesoreria"].'&vaMatricula='.$_POST["va_matricula"].'&inclusion='.$_POST["inclusion"].'&extran='.$_POST["extran"].'&tipoSangre='.$_POST["tipoSangre"].'&eps='.$_POST["eps"].'&celular2='.$_POST["celular2"].'&ciudadR='.$_POST["ciudadR"].'&nombre2='.$_POST["nombre2"].'&documentoA='.$_POST["documentoA"].'&nombreA='.$_POST["nombreA"].'&ocupacionA='.$_POST["ocupacionA"].'&generoA='.$_POST["generoA"].'&expedicionA='.$_POST["lugardA"].'&tipoDocA='.$_POST["tipoDAcudiente"].'&apellido1A='.$_POST["apellido1A"].'&apellido2A='.$_POST["apellido2A"].'&nombre2A='.$_POST["nombre2A"].'&matestM='.$_POST["matestM"].'";</script>';
	exit();
}
//VALIDAMOS QUE EL ESTUDIANTE NO SE ENCUENTRE CREADO
$valiEstudiante = Estudiantes::validarExistenciaEstudiante($_POST["nDoc"]);
if($valiEstudiante > 0){

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="estudiantes-agregar.php?error=ER_DT_5&tipoD='.$_POST["tipoD"].'&documento='.$_POST["nDoc"].'&religion='.$_POST["religion"].'&email='.$_POST["email"].'&direcion='.$_POST["direccion"].'&barrio='.$_POST["barrio"].'&telefono='.$_POST["telefono"].'&celular='.$_POST["celular"].'&estrato='.$_POST["estrato"].'&genero='.$_POST["genero"].'&nacimiento='.$_POST["fNac"].'&apellido1='.$_POST["apellido1"].'&apellido2='.$_POST["apellido2"].'&nombre='.$_POST["nombres"].'&grado='.$_POST["grado"].'&grupo='.$_POST["grupo"].'&tipoE='.$_POST["tipoEst"].'&lugarEx='.$_POST["lugarD"].'&lugarNac='.$_POST["lNac"].'&matricula='.$_POST["matricula"].'&folio='.$_POST["folio"].'&tesoreria='.$_POST["codTesoreria"].'&vaMatricula='.$_POST["va_matricula"].'&inclusion='.$_POST["inclusion"].'&extran='.$_POST["extran"].'&tipoSangre='.$_POST["tipoSangre"].'&eps='.$_POST["eps"].'&celular2='.$_POST["celular2"].'&ciudadR='.$_POST["ciudadR"].'&nombre2='.$_POST["nombre2"].'&documentoA='.$_POST["documentoA"].'&nombreA='.$_POST["nombreA"].'&ocupacionA='.$_POST["ocupacionA"].'&generoA='.$_POST["generoA"].'&expedicionA='.$_POST["lugardA"].'&tipoDocA='.$_POST["tipoDAcudiente"].'&apellido1A='.$_POST["apellido1A"].'&apellido2A='.$_POST["apellido2A"].'&nombre2A='.$_POST["nombre2A"].'&matestM='.$_POST["matestM"].'";</script>';
	exit();
}

$result_numMat = strtotime("now");


if(empty($_POST["tipoMatricula"])){ $_POST["tipoMatricula"]=GRADO_GRUPAL;}

//Establecer valores por defecto cuando los campos vengan vacíos
if($_POST["va_matricula"]=="") $_POST["va_matricula"] = 0;
if($_POST["grupo"]=="")        $_POST["grupo"]        = 4;
if($_POST["tipoEst"]=="")      $_POST["tipoEst"]      = 128;
if($_POST["fNac"]=="")         $_POST["fNac"]         = '2000-01-01';
if($_POST["tipoD"]=="")        $_POST["tipoD"]        = 107;
if($_POST["genero"]=="")       $_POST["genero"]       = 126;

if($_POST["religion"]=="")     $_POST["religion"]     = 112;
if($_POST["estrato"]=="")      $_POST["estrato"]      = 116;
if($_POST["extran"]=="")       $_POST["extran"]       = 0;
if($_POST["inclusion"]=="")    $_POST["inclusion"]    = 0;
if($_POST["tipoMatricula"]=="")$_POST["tipoMatricula"]    = GRADO_GRUPAL;

//Api solo para Icolven
if($config['conf_id_institucion']==1){
	require_once("apis-sion-create-student.php");
}
$procedencia=$_POST["lNacM"];
if(!empty($_POST["ciudadPro"]) && !is_numeric($_POST["ciudadPro"])){
	$procedencia=$_POST["ciudadPro"];
}

try{
	$acudienteConsulta = mysqli_query($conexion, "SELECT * FROM usuarios WHERE uss_usuario='".$_POST["documentoA"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
$acudienteNum = mysqli_num_rows($acudienteConsulta);
$acudienteDatos = mysqli_fetch_array($acudienteConsulta, MYSQLI_BOTH);
//PREGUNTAMOS SI EL ACUDIENTE EXISTE
if ($acudienteNum > 0) {	
	$idAcudiente = $acudienteDatos[0];
} else {
	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if(trim($_POST["documentoA"])=="" or trim($_POST["nombresA"])==""){

		include("../compartido/guardar-historial-acciones.php");
		echo '<script type="text/javascript">window.location.href="estudiantes-agregar.php?error=ER_DT_6&tipoD='.$_POST["tipoD"].'&documento='.$_POST["nDoc"].'&religion='.$_POST["religion"].'&email='.$_POST["email"].'&direcion='.$_POST["direccion"].'&barrio='.$_POST["barrio"].'&telefono='.$_POST["telefono"].'&celular='.$_POST["celular"].'&estrato='.$_POST["estrato"].'&genero='.$_POST["genero"].'&nacimiento='.$_POST["fNac"].'&apellido1='.$_POST["apellido1"].'&apellido2='.$_POST["apellido2"].'&nombre='.$_POST["nombres"].'&grado='.$_POST["grado"].'&grupo='.$_POST["grupo"].'&tipoE='.$_POST["tipoEst"].'&lugarEx='.$_POST["lugarD"].'&lugarNac='.$_POST["lNac"].'&matricula='.$_POST["matricula"].'&folio='.$_POST["folio"].'&tesoreria='.$_POST["codTesoreria"].'&vaMatricula='.$_POST["va_matricula"].'&inclusion='.$_POST["inclusion"].'&extran='.$_POST["extran"].'&tipoSangre='.$_POST["tipoSangre"].'&eps='.$_POST["eps"].'&celular2='.$_POST["celular2"].'&ciudadR='.$_POST["ciudadR"].'&nombre2='.$_POST["nombre2"].'&documentoA='.$_POST["documentoA"].'&nombreA='.$_POST["nombreA"].'&ocupacionA='.$_POST["ocupacionA"].'&generoA='.$_POST["generoA"].'&expedicionA='.$_POST["lugardA"].'&tipoDocA='.$_POST["tipoDAcudiente"].'&apellido1A='.$_POST["apellido1A"].'&apellido2A='.$_POST["apellido2A"].'&nombre2A='.$_POST["nombre2A"].'&matestM='.$_POST["matestM"].'";</script>';
		exit();
	}

	if($_POST["generoA"]=="")       $_POST["generoA"]       = 126;
	
	//CREAMOS AL ACUDIENTE
	try{
		mysqli_query($conexion, "INSERT INTO usuarios(
			uss_usuario, 
			uss_clave, 
			uss_tipo, 
			uss_nombre, 
			uss_estado, 
			uss_ocupacion, 
			uss_email, 
			uss_fecha_nacimiento, 
			uss_permiso1, 
			uss_genero, 
			uss_celular, 
			uss_foto,
			uss_idioma,
			uss_tipo_documento, 
			uss_lugar_expedicion, 
			uss_direccion, 
			uss_apellido1, 
			uss_apellido2, 
			uss_nombre2,
			uss_documento, 
			uss_tema_sidebar,
			uss_tema_header,
			uss_tema_logo
			)VALUES(
			'".$_POST["documentoA"]."',
			'".$clavePorDefectoUsuarios."',
			3,
			'".$_POST["nombresA"]."',
			0,
			'".$_POST["ocupacionA"]."',
			'".$_POST["email"]."',
			'".$_POST["fechaNA"]."',
			0,
			'".$_POST["generoA"]."',
			'".$_POST["celular"]."', 
			'default.png',
			1,
			'".$_POST["tipoDAcudiente"]."',
			'".$_POST["lugarDa"]."', 
			'".$_POST["direccion"]."', 
			'".$_POST["apellido1A"]."', 
			'".$_POST["apellido2A"]."', 
			'".$_POST["nombre2A"]."',
			'".	$_POST["documentoA"]."',
			'cyan-sidebar-color',
			'header-indigo',
			'logo-indigo'
			)");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	
	$idAcudiente = mysqli_insert_id($conexion);
}

//INSERTAMOS EL USUARIO ESTUDIANTE
try{
	mysqli_query($conexion, "INSERT INTO usuarios(
		uss_usuario, 
		uss_clave, 
		uss_tipo, 
		uss_nombre, 
		uss_estado, 
		uss_email, 
		uss_fecha_nacimiento, 
		uss_permiso1, 
		uss_genero, 
		uss_celular, 
		uss_foto, 
		uss_idioma, 
		uss_tipo_documento, 
		uss_lugar_expedicion, 
		uss_direccion, 
		uss_apellido1, 
		uss_apellido2, 
		uss_nombre2,
		uss_documento, 
		uss_tema_sidebar,
		uss_tema_header,
		uss_tema_logo
		)VALUES(
		'".	$_POST["nDoc"]."',
		'".$clavePorDefectoUsuarios."',
		4,
		'".$_POST["nombres"]."',
		0,
		'".strtolower($_POST["email"])."',
		'".$_POST["fNac"]."',
		0,
		'".$_POST["genero"]."',
		'".$_POST["celular"]."', 
		'default.png', 
		1, 
		'".$_POST["tipoD"]."',
		'".$_POST["lugarD"]."', 
		'".$_POST["direccion"]."', 
		'".$_POST["apellido1"]."', 
		'".$_POST["apellido2"]."', 
		'".$_POST["nombre2"]."',
		'".	$_POST["nDoc"]."',
		'cyan-sidebar-color',
		'header-indigo',
		'logo-indigo'
		)");
		$idEstudianteU = mysqli_insert_id($conexion);
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

//Insertamos la matrícula
try{
	mysqli_query($conexion, "INSERT INTO academico_matriculas(
		mat_matricula, mat_fecha, mat_tipo_documento, 
		mat_documento, mat_religion, mat_email, 
		mat_direccion, mat_barrio, mat_telefono, 
		mat_celular, mat_estrato, mat_genero, 
		mat_fecha_nacimiento, mat_primer_apellido, mat_segundo_apellido, 
		mat_nombres, mat_grado, mat_grupo, 
		mat_tipo, mat_lugar_nacimiento, mat_lugar_expedicion, 
		mat_acudiente, mat_estado_matricula, mat_id_usuario, 
		mat_folio, mat_codigo_tesoreria, mat_valor_matricula, 
		mat_inclusion, mat_extranjero, mat_tipo_sangre, 
		mat_eps, mat_celular2, mat_ciudad_residencia, 
		mat_nombre2,mat_tipo_matricula)
		VALUES(
		".$result_numMat.", now(), ".$_POST["tipoD"].",
		".$_POST["nDoc"].", ".$_POST["religion"].", '".strtolower($_POST["email"])."',
		'".$_POST["direccion"]."', '".$_POST["barrio"]."', '".$_POST["telefono"]."',
		'".$_POST["celular"]."', ".$_POST["estrato"].", ".$_POST["genero"].", 
		'".$_POST["fNac"]."', '".$_POST["apellido1"]."', '".$_POST["apellido2"]."', 
		'".$_POST["nombres"]."', '".$_POST["grado"]."', '".$_POST["grupo"]."',
		'".$_POST["tipoEst"]."', '".$procedencia."', '".$_POST["lugarD"]."',
		".$idAcudiente.", '".$_POST["matestM"]."', '".$idEstudianteU."', 
		'".$_POST["folio"]."', '".$_POST["codTesoreria"]."', '".$_POST["va_matricula"]."', 
		'".$_POST["inclusion"]."', '".$_POST["extran"]."', '".$_POST["tipoSangre"]."', 
		'".$_POST["eps"]."', '".$_POST["celular2"]."', '".$_POST["ciudadR"]."', 
		'".$_POST["nombre2"]."','".$_POST["tipoMatricula"]."'
		)");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
$idEstudiante = mysqli_insert_id($conexion);

//Insertamos las matrículas Adicionales
if ($_POST["tipoMatricula"]==GRADO_INDIVIDUAL && !empty($_POST["cursosAdicionales"])) { 
	try{
		MediaTecnicaServicios::guardar($idEstudiante,$_POST["cursosAdicionales"],$config);
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}
try{
	mysqli_query($conexion, "INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$idAcudiente."', '".$idEstudiante."')");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}


if(!isset($estado) AND !isset($mensaje)){
	$estado="";
	$mensaje="";
}
$idUsr = mysqli_insert_id($conexion);
$estadoSintia=false;
$mensajeSintia='El estudiante no pudo ser creado correctamente en SINTIA.';
if(isset($idUsr) AND $idUsr!=''){
	$estadoSintia=true;
	$mensajeSintia='El estudiante fue creado correctamente en SINTIA.';
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="estudiantes-editar.php?id='.$idEstudiante.'&stadsion='.$estado.'&msgsion='.$mensaje.'&stadsintia='.$estadoSintia.'&msgsintia='.$mensajeSintia.'";</script>';
exit();