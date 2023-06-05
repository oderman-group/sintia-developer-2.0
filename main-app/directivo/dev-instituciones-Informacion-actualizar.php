<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0035';
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["rectorI"]) == "" or trim($_POST["secretarioI"]) == "" or trim($_POST["nitI"]) == "" or trim($_POST["nomInstI"]) == "" or trim($_POST["direccionI"]) == "" or trim($_POST["telI"]) == "" or trim($_POST["calseI"]) == "" or trim($_POST["caracterI"]) == "" or trim($_POST["calendarioI"]) == "" or trim($_POST["jornadaI"]) == "" or trim($_POST["horarioI"]) == "" or trim($_POST["nivelesI"]) == "" or trim($_POST["modalidadI"]) == "" or trim($_POST["propietarioI"]) == "" or trim($_POST["coordinadorI"]) == "" or trim($_POST["tesoreroI"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
		exit();
	}
	if ($_FILES['logo']['name'] != "") {
		$archivo = $_FILES['logo']['name'];
		$archivoAnt = $_POST["logoAnterior"];
		$destino = "../files/images/logo/";
		@unlink($destino . "/" . $archivoAnt);
		move_uploaded_file($_FILES['logo']['tmp_name'], $destino . "/" . $archivo);
	} else {
		$archivo = $_POST["logoAnterior"];
	}

	try{
		mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".general_informacion SET info_rector='" . $_POST["rectorI"] . "', info_secretaria_academica='" . $_POST["secretarioI"] . "', info_logo='" . $archivo . "', info_nit='" . $_POST["nitI"] . "', info_nombre='" . $_POST["nomInstI"] . "', info_direccion='" . $_POST["direccionI"] . "', info_telefono='" . $_POST["telI"] . "', info_clase='" . $_POST["calseI"] . "', info_caracter='" . $_POST["caracterI"] . "',info_calendario='" . $_POST["calendarioI"] . "', info_jornada='" . $_POST["jornadaI"] . "', info_horario='" . $_POST["horarioI"] . "', info_niveles='" . $_POST["nivelesI"] . "', info_modalidad='" . $_POST["modalidadI"] . "', info_propietario='" . $_POST["propietarioI"] . "', info_coordinador_academico='" . $_POST["coordinadorI"] . "', info_tesorero='" . $_POST["tesoreroI"] . "'
		WHERE info_institucion='" . $_POST["id"] . "' AND info_year='" . $_POST["year"] . "'");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-instituciones-Informacion.php?success=SC_DT_2&id='.$_POST["id"].'&year='.$_POST["year"].'";</script>';
	exit();