<?php
include("session.php");
$idPaginaInterna = 'DT0131';
include("../compartido/sintia-funciones.php");
include("../compartido/guardar-historial-acciones.php");
require_once("../class/SubRoles.php");

if (!empty($_FILES['fotoUss']['name'])) {
	$explode = explode(".", $_FILES['fotoUss']['name']);
	$extension = end($explode);

	if($extension != 'jpg' && $extension != 'png'){
		echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id='.$_POST["idR"].'&error=ER_DT_8";</script>';
		exit();
	}

	$archivo = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_img_') . "." . $extension;
	$destino = "../files/fotos";
	move_uploaded_file($_FILES['fotoUss']['tmp_name'], $destino . "/" . $archivo);
	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_foto='" . $archivo . "' WHERE uss_id='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
	if($_POST["tipoUsuario"]==4){
		try{
			mysqli_query($conexion, "UPDATE academico_matriculas SET mat_foto='" . $archivo . "' WHERE mat_id_usuario='" . $_POST["idR"] . "'");
		} catch (Exception $e) {
			include("../compartido/error-catch-to-report.php");
		}
	}
}
try{
	mysqli_query($conexion, "UPDATE usuarios SET 
	uss_usuario=           '" . $_POST["usuario"] . "', 
	uss_tipo=              " . $_POST["tipoUsuario"] . ", 
	uss_nombre=            '" . $_POST["nombre"] . "',
	uss_email=             '" . strtolower($_POST["email"]) . "', 
	uss_genero=            '" . $_POST["genero"] . "',
	uss_celular=           '" . $_POST["celular"] . "',
	uss_ocupacion=         '" . $_POST["ocupacion"] . "',
	uss_lugar_expedicion=  '" . $_POST["lExpedicion"] . "',
	uss_direccion=         '" . $_POST["direccion"] . "',
	uss_telefono=          '" . $_POST["telefono"] . "',
	uss_intentos_fallidos= '" . $_POST["intentosFallidos"] . "',
	uss_tipo_documento=    '" . $_POST["tipoD"] . "', 
	uss_apellido1=         '" . $_POST["apellido1"] . "', 
	uss_apellido2=         '" . $_POST["apellido2"] . "', 
	uss_nombre2=           '" . $_POST["nombre2"] . "', 
	uss_documento=         '" . $_POST["documento"] . "',

	uss_ultima_actualizacion=now()
	WHERE uss_id='" . $_POST["idR"] . "'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

if (!empty($_POST["clave"]) && $_POST["cambiarClave"] == 1) {

	$validarClave=validarClave($_POST["clave"]);
	if($validarClave!=true){
		echo '<script type="text/javascript">window.location.href="usuarios-editar.php?error=5&id='.$_POST["idR"].'";</script>';
		exit();
	}

	try{
		mysqli_query($conexion, "UPDATE usuarios SET uss_clave= SHA1('" . $_POST["clave"] . "')
		WHERE uss_id='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

}


if ($_POST["tipoUsuario"] == 4) {
	try{
		mysqli_query($conexion, "UPDATE academico_matriculas SET mat_email='" . strtolower($_POST["email"]) . "'
		WHERE mat_id_usuario='" . $_POST["idR"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

if(!empty($_POST["subroles"])){
	try{
		$listaRoles=SubRoles::crearRolesUsuario($_POST["idR"],$_POST["subroles"]);
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

echo '<script type="text/javascript">window.location.href="usuarios-editar.php?id='.$_POST["idR"].'&success=SC_DT_2";</script>';
exit();