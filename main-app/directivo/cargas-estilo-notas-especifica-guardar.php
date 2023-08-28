<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0181';

if(!Modulos::validarSubRol($idPaginaInterna)){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

	//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
	if (trim($_POST["nombreCN"]) == "" or trim($_POST["ndesdeCN"]) == "" or trim($_POST["nhastaCN"]) == "" or trim($_POST["idCN"]) == "") {
		include("../compartido/guardar-historial-acciones.php");
		echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
			<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
		exit();
	}
	try{
		mysqli_query($conexion, "INSERT INTO academico_notas_tipos (notip_nombre, notip_desde, notip_hasta,notip_categoria)VALUES('" . $_POST["nombreCN"] . "'," . $_POST["ndesdeCN"] . "," . $_POST["nhastaCN"] . "," . $_POST["idCN"] . ");");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}

	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="cargas-estilo-notas-especifica.php?id=' . $_POST["idCN"] . '";</script>';
	exit();