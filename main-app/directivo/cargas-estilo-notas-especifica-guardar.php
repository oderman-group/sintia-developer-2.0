<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0181';

if(!Modulos::validarSubRol([$idPaginaInterna])){
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

CargaAcademica::guardarTipoNota($conexion, $conexionPDO, $config, $_POST);

include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="cargas-estilo-notas-especifica.php?id=' . base64_encode($_POST["idCN"]) . '";</script>';
exit();