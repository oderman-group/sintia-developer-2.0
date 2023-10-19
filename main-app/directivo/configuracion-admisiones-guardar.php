<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0015';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

include("../compartido/sintia-funciones.php");
$archivoSubido = new Archivos;

if(empty($_POST["valorInscripcion"])) {$_POST["valorInscripcion"] = 0;}

$mostrarBanner=0;
if(!empty($_POST["mostrarBanner"])) {$mostrarBanner=1;}

$archivo = $_POST["cfgi_politicas_adjunto"];
if (!empty($_FILES['politicasArchivo']['name'])) {
	$archivoSubido->validarArchivo($_FILES['politicasArchivo']['size'], $_FILES['politicasArchivo']['name']);
	$explode=explode(".", $_FILES['politicasArchivo']['name']);
	$extension = end($explode);
	$archivo = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_politicaAd_') . "." . $extension;
	$destino = "../files/imagenes-generales";
	move_uploaded_file($_FILES['politicasArchivo']['tmp_name'], $destino . "/" . $archivo);
}

$bannerInicio = $_POST["cfgi_banner_inicial"];
if (!empty($_FILES['bannerInicio']['name'])) {
	$archivoSubido->validarArchivo($_FILES['bannerInicio']['size'], $_FILES['bannerInicio']['name']);
	$explode=explode(".", $_FILES['bannerInicio']['name']);
	$extension = end($explode);
	$bannerInicio = uniqid($_SESSION["inst"] . '_' . $_SESSION["id"] . '_bannerAd_') . "." . $extension;
	$destino = "../files/imagenes-generales";
	move_uploaded_file($_FILES['bannerInicio']['tmp_name'], $destino . "/" . $bannerInicio);
}

$datosCuenta=!empty($_POST["datosCuenta"])? str_replace(["<p>", "</p>"], "",$_POST["datosCuenta"]):"";

try {
	mysqli_query($conexion, "UPDATE ".$baseDatosAdmisiones.".config_instituciones SET 
	cfgi_valor_inscripcion='" . $_POST["valorInscripcion"] . "', 
	cfgi_color_barra_superior='" . $_POST["colorFondo"] . "', 
	cfgi_color_texto='" . $_POST["colorTexto"] . "', 
	cfgi_inscripciones_activas='" . $_POST["habilitarInscripcion"] . "', 
	cfgi_texto_inicial='" . $_POST["textoInicial"] . "', 
	cfgi_politicas_texto='" . $_POST["politicas"] . "',
	cfgi_politicas_adjunto='" . $archivo . "',
	cfgi_banner_inicial='" . $bannerInicio . "',
	cfgi_activar_boton_pagar_prematricula='" . $_POST["habilitarPagoPrematricula"] . "',
	cfgi_link_boton_pagar_prematricula='" . $_POST["linkPagoPrematricula"] . "',
	cfgi_mostrar_banner='" . $mostrarBanner . "',
	cfgi_mostrar_politicas='" . $_POST["mostrarPoliticas"] . "',
	cfgi_texto_info_cuenta='" . $datosCuenta . "',
	cfgi_year_inscripcion='" . $_POST["yearInscripcion"] . "'
	WHERE cfgi_id='".$_POST["id"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include("../compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="configuracion-admisiones.php";</script>';
exit();