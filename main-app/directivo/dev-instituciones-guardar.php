<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0015';
include("../compartido/historial-acciones-guardar.php");

if (trim($_POST["nombreInstitucion"]) == "") {
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-instituciones-editar.php?error=ER_DT_4";</script>';
	exit();
}
$fechaRenovacion="";
if(!empty($_POST["fechaRenovacion"])){
	$fechaRenovacion="ins_fecha_renovacion='" . $_POST["fechaRenovacion"] . "', ";
}

try {
	mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".instituciones SET 
	ins_nit='" . $_POST["nit"] . "', 
	ins_nombre='" . $_POST["nombreInstitucion"] . "', 
	ins_siglas='" . $_POST["siglas"] . "', 
	ins_telefono_principal='" . $_POST["telefonoPrincipal"] . "', 
	ins_email_institucion='" . $_POST["emailPrincipal"] . "', 
	ins_ciudad='" . $_POST["ciudad"] . "', 
	$fechaRenovacion
	ins_contacto_principal='" . $_POST["contactoPrincipal"] . "',
	ins_cargo_contacto='" . $_POST["cargo"] . "',
	ins_celular_contacto='" . $_POST["celular"] . "', 
	ins_email_contacto='" . $_POST["email"] . "',
	ins_estado='" . $_POST["estado"] . "',
	ins_id_plan='" . $_POST["plan"] . "', 
	ins_deuda='" . $_POST["deuda"] . "',
	ins_valor_deuda='" . $_POST["valorDeuda"] . "',
	ins_concepto_deuda='" . $_POST["conceptoDeuda"] . "'
	WHERE ins_id='".$_POST['id']."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

	
	$numModulos = (count($_POST["modulos"]));
	try{
		mysqli_query($conexion,"DELETE FROM ".$baseDatosServicios.".instituciones_modulos WHERE ipmod_institucion='".$_POST['id']."'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	if($numModulos>0){
		$contModulos = 0;
		while ($contModulos < $numModulos) {
			try{
				mysqli_query($conexion,"INSERT INTO ".$baseDatosServicios.".instituciones_modulos (ipmod_institucion,ipmod_modulo) VALUES ('".$_POST['id']."', '".$_POST["modulos"][$contModulos]."')");
			} catch (Exception $e) {
				include("../compartido/error-catch-to-report.php");
			}
			$contModulos++;
		}
	}
	
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-instituciones.php";</script>';
	exit();