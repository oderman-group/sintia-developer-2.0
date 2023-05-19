<?php
include("session.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'DT0168';
include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if (trim($_POST["nombreCN"]) == "" or trim($_POST["ndesdeCN"]) == "" or trim($_POST["nhastaCN"]) == "" or trim($_POST["idCN"]) == "") {
	echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.<br>
		<a href='javascript:history.go(-1)'>[Volver al formulario]</a></samp>";
	exit();
}

try{
	mysqli_query($conexion, "UPDATE academico_notas_tipos SET notip_nombre='" . $_POST["nombreCN"] . "', notip_desde=" . $_POST["ndesdeCN"] . ", notip_hasta=" . $_POST["nhastaCN"] . " WHERE notip_id=" . $_POST["idN"] . ";");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

	echo '<script type="text/javascript">window.location.href="cargas-estilo-notas-especifica.php?id=' . $_POST["idCN"] . '";</script>';
	exit();