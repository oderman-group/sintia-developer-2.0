<?php 
include("session.php");
require_once("../class/Estudiantes.php");
require_once("../class/Usuarios.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0174';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

require_once("../class/servicios/MediaTecnicaServicios.php");
//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if(trim($_POST["nDoc"])=="" or trim($_POST["apellido1"])=="" or trim($_POST["nombres"])==""){
	echo '<script type="text/javascript">window.location.href="estudiantes-editar.php?id='.base64_encode($_POST["id"]).'&error=ER_DT_4";</script>';
	exit();
}
$validacionEstudiante = Estudiantes::validarRepeticionDocumento($_POST["nDoc"], $_POST["id"]);

if($validacionEstudiante > 0){
	echo '<script type="text/javascript">window.location.href="estudiantes-editar.php?id='.base64_encode($_POST["id"]).'&documento='.base64_encode($_POST["nDoc"]).'&error=ER_DT_11";</script>';
	exit();
}

$estado='';
$mensaje='';
$pasosMatricula='';
if($config['conf_id_institucion'] == ICOLVEN){
	$pasosMatricula="
		mat_iniciar_proceso='".$_POST["iniciarProceso"]."',
		mat_actualizar_datos='".$_POST["actualizarDatos"]."',
		mat_pago_matricula='".$_POST["pagoMatricula"]."',
		mat_contrato='".$_POST["contrato"]."',
		mat_pagare='".$_POST["pagare"]."',
		mat_compromiso_academico='".$_POST["compromisoA"]."',
		mat_compromiso_convivencia='".$_POST["compromisoC"]."',
		mat_manual='".$_POST["manual"]."',
		mat_mayores14='".$_POST["contrato14"]."',
		mat_compromiso_convivencia_opcion='".$_POST["compromisoOpcion"]."',
		mat_hoja_firma='".$_POST["firmaHoja"]."',
	";
	require_once("apis-sion-modify-student.php");
}
$fechaNacimiento="";
if(!empty($_POST["fNac"])){
	$fechaNacimiento="mat_fecha_nacimiento='" . $_POST["fNac"] . "', ";
}
$_POST["ciudadR"] = trim($_POST["ciudadR"]);
if($_POST["va_matricula"]==""){$_POST["va_matricula"]=0;}

$esMediaTecnica=!is_null($_POST["tipoMatricula"]);
if(!$esMediaTecnica){
	$datosEstudianteActual = Estudiantes::obtenerDatosEstudiante($_POST["id"]);
	$_POST["tipoMatricula"]=$datosEstudianteActual["mat_tipo_matricula"];
}
if(empty($_POST["tipoMatricula"])){ $_POST["tipoMatricula"]=GRADO_GRUPAL;}

$procedencia=$_POST["lNac"];
if(!empty($_POST["ciudadPro"]) && !is_numeric($_POST["ciudadPro"])){
	$procedencia=$_POST["ciudadPro"];
}
if (!empty($_FILES['fotoMat']['name'])) {
	$explode = explode(".", $_FILES['fotoMat']['name']);
	$extension = end($explode);

	if($extension != 'jpg' && $extension != 'png'){
		echo '<script type="text/javascript">window.location.href="estudiantes-editar.php?id='.base64_encode($_POST["id"]).'&error=ER_DT_8";</script>';
		exit();
	}

	$archivo = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_img_') . "." . $extension;
	$destino = "../files/fotos";
	move_uploaded_file($_FILES['fotoMat']['tmp_name'], $destino . "/" . $archivo);
	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_foto='" . $archivo . "' WHERE mat_id='" . $_POST["id"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_foto='" . $archivo . "' WHERE uss_id='" . $_POST["idU"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

Estudiantes::actualizarEstudiantes($conexionPDO, $_POST, $fechaNacimiento, $procedencia, $pasosMatricula);

if ($esMediaTecnica) { 
	try{
		if($_POST["tipoMatricula"] ==GRADO_INDIVIDUAL)
		MediaTecnicaServicios::editar($_POST["id"],$_POST["cursosAdicionales"],$config,$_POST["grupoMT"]);
		else
		MediaTecnicaServicios::editar($_POST["id"],$arregloVacio,$config);
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

try {
	mysqli_query($conexion, "UPDATE usuarios SET uss_usuario='".$_POST["nDoc"]."' WHERE uss_id='".$_POST["idU"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}	

//ACTUALIZAR EL ACUDIENTE 1	
if($_POST["documentoA"]!=""){

	try {
		$consultaIdAcudiente=mysqli_query($conexion, "SELECT mat_acudiente FROM academico_matriculas WHERE mat_id='".$_POST["id"]."'");
		$datosIdAcudiente = mysqli_fetch_array($consultaIdAcudiente, MYSQLI_BOTH);
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	$usuarioAcudiente=$_POST["documentoA"];
	if(!empty($datosIdAcudiente['mat_acudiente']) && $datosIdAcudiente['mat_acudiente']!=0){
		$usuarioAcudiente=$datosIdAcudiente['mat_acudiente'];
	}

	try {
		$acudiente = Usuarios::obtenerDatosUsuario($usuarioAcudiente);
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}		

	if(!empty($acudiente)){
		try {
			mysqli_query($conexion, "UPDATE usuarios SET 
			uss_usuario   		 = '".$_POST["documentoA"]."', 
			uss_nombre    		 = '".$_POST["nombreA"]."', 
			uss_email     		 = '".$_POST["email"]."', 
			uss_ocupacion 		 = '".$_POST["ocupacionA"]."', 
			uss_genero    		 = '".$_POST["generoA"]."', 
			uss_celular   		 = '".$_POST["celular"]."', 
			uss_lugar_expedicion = '".$_POST["lugardA"]."', 
			uss_tipo_documento   = '".$_POST["tipoDAcudiente"]."', 
			uss_direccion        = '".$_POST["direccion"]."', 
			uss_apellido1 		 = '".$_POST["apellido1A"]."', 
			uss_apellido2		 = '".$_POST["apellido2A"]."', 
			uss_nombre2			 = '".$_POST["nombre2A"]."', 
			uss_documento		 = '".$_POST["documentoA"]."' 
			WHERE uss_id='".$acudiente['uss_id']."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$idAcudiente = $acudiente['uss_id'];
	}else{
		try {
			mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_ocupacion, uss_email, uss_fecha_nacimiento, uss_permiso1, uss_genero, uss_celular, uss_foto, uss_idioma, uss_tipo_documento, uss_lugar_expedicion, uss_direccion, uss_apellido1, uss_apellido2, uss_nombre2, uss_documento, uss_tema_sidebar, uss_tema_header, uss_tema_logo)VALUES('".$_POST["documentoA"]."', '".$clavePorDefectoUsuarios."', 3, '".$_POST["nombreA"]."', 0, '".$_POST["ocupacionA"]."', '".$_POST["email"]."', '".$_POST["fechaNA"]."', 0, '".$_POST["generoA"]."', '".$_POST["celular"]."', 'default.png', 1, '".$_POST["tipoDAcudiente"]."', '".$_POST["lugardA"]."', '".$_POST["direccion"]."', '".$_POST["apellido1A"]."', '".$_POST["apellido2A"]."', '".$_POST["nombre2A"]."', '".	$_POST["documentoA"]."', 'cyan-sidebar-color', 'header-indigo', 'logo-indigo')");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
		$idAcudiente = mysqli_insert_id($conexion);
	}

	try {
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente='".$idAcudiente."' WHERE mat_id='".$_POST["id"]."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}	

	try {
		mysqli_query($conexion, "DELETE FROM usuarios_por_estudiantes 
		WHERE upe_id_estudiante='".$_POST["id"]."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}	

	try {
		mysqli_query($conexion, "INSERT INTO usuarios_por_estudiantes(upe_id_usuario, upe_id_estudiante)VALUES('".$idAcudiente."', '".$_POST["id"]."')");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}	
}


//ACTUALIZAR EL ACUDIENTE 2
if(!empty($_POST["idAcudiente2"])){

	try {
		mysqli_query($conexion, "UPDATE usuarios SET 
		uss_usuario='".$_POST["documentoA2"]."', 
		uss_nombre='".$_POST["nombreA2"]."', 
		uss_email='".$_POST["email"]."', 
		uss_ocupacion='".$_POST["ocupacionA2"]."', 
		uss_genero='".$_POST["generoA2"]."', 
		uss_celular='".$_POST["celular"]."', 
		uss_lugar_expedicion='".$_POST["lugardA2"]."', 
		uss_direccion='".$_POST["direccion"]."', 
		uss_apellido1='".$_POST["apellido1A2"]."', 
		uss_apellido2='".$_POST["apellido2A2"]."', 
		uss_nombre2='".$_POST["nombre2A2"]."', 
		uss_documento= '".$_POST["documentoA2"]."' 
		WHERE uss_id='".$_POST["idAcudiente2"]."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}	

}else {
	if(!empty($_POST["documentoA2"])){
	
		try {
			$existeAcudiente2 = Usuarios::validarExistenciaUsuario($_POST["documentoA2"]);
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	
		if($existeAcudiente2>0){
	
			try {
				$acudiente2 = Usuarios::obtenerDatosUsuario($_POST["documentoA2"]);
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}

			try {
				mysqli_query($conexion, "UPDATE usuarios SET 
				uss_usuario='".$_POST["documentoA2"]."', 
				uss_nombre='".$_POST["nombreA2"]."', 
				uss_email='".$_POST["email"]."', 
				uss_ocupacion='".$_POST["ocupacionA2"]."', 
				uss_genero='".$_POST["generoA2"]."', 
				uss_celular='".$_POST["celular"]."', 
				uss_lugar_expedicion='".$_POST["lugardA2"]."', 
				uss_direccion='".$_POST["direccion"]."', 
				uss_apellido1='".$_POST["apellido1A2"]."', 
				uss_apellido2='".$_POST["apellido2A2"]."', 
				uss_nombre2='".$_POST["nombre2A2"]."', 
				uss_documento= '".$_POST["documentoA2"]."' 
				WHERE uss_id='".$acudiente2['uss_id']."'");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$idAcudiente2 = $acudiente2['uss_id'];
		}else{
			try {
				mysqli_query($conexion, "INSERT INTO usuarios(uss_usuario, uss_clave, uss_tipo, uss_nombre, uss_estado, uss_ocupacion, uss_email, uss_permiso1, uss_genero, uss_celular, uss_foto, uss_portada, uss_idioma, uss_tema, uss_lugar_expedicion, uss_direccion, uss_apellido1, uss_apellido2, uss_nombre2, uss_documento)VALUES('".$_POST["documentoA2"]."','".$clavePorDefectoUsuarios."',3,'".$_POST["nombreA2"]."',0,'".$_POST["ocupacionA2"]."','".$_POST["email"]."',0,'".$_POST["generoA2"]."','".$_POST["celular"]."', 'default.png', 'default.png', 1, 'green', '".$_POST["lugardA2"]."', '".$_POST["direccion"]."', '".$_POST["apellido1A2"]."', '".$_POST["apellido2A2"]."', '".$_POST["nombre2A2"]."','".$_POST["documentoA2"]."')");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$idAcudiente2 = mysqli_insert_id($conexion);
		}
	
		try {
			mysqli_query($conexion, "UPDATE academico_matriculas SET mat_acudiente2='".$idAcudiente2."' WHERE mat_id='".$_POST["id"]."'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
}
include("../compartido/guardar-historial-acciones.php");

$estadoSintia=true;
$mensajeSintia='La información del estudiante se actualizó correctamente en SINTIA.';

echo '<script type="text/javascript">window.location.href="estudiantes-editar.php?id='.base64_encode($_POST["id"]).'&stadsion='.base64_encode($estado).'&msgsion='.base64_encode($mensaje).'&stadsintia='.base64_encode($estadoSintia).'&msgsintia='.base64_encode($mensajeSintia).'";</script>';
exit();