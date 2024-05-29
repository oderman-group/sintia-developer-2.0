<?php 
include("session.php");
require_once(ROOT_PATH."/main-app/class/Grados.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0338';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(empty($_POST["posicionLogo"])) {$_POST["posicionLogo"] = NULL;}
if(empty($_POST["bannerAnterior"])) {$_POST["bannerAnterior"] = NULL;}

if (!empty($_FILES['banner']['name'])) {
	$explode = explode(".", $_FILES['banner']['name']);
	$extension = end($explode);
	$archivo = uniqid('banner_') . "." . $extension;
	$archivoAnt = $_POST["bannerAnterior"];
	$destino = ROOT_PATH."/main-app/files/conf_boletin/";
	@unlink($destino . "/" . $archivoAnt);
	move_uploaded_file($_FILES['banner']['tmp_name'], $destino . "/" . $archivo);
} else {
	$archivo = $_POST["bannerAnterior"];
}

if(!empty($_POST['id'])) {

	$update ="
		conbol_tipo_encabezado=" . $_POST["encabezado"] . ", 
		conbol_posicion_logo=" . $_POST["posicionLogo"] . ", 
		conbol_mostrar_areas=" . $_POST["areas"] . ", 
		conbol_mostrar_materias=" . $_POST["materias"] . ", 
		conbol_mostrar_indicadores=" . $_POST["indicadores"] . ",  
		conbol_mostrar_observaciones_materia=" . $_POST["observaciones"] . ",
		conbol_mostrar_rango_notas=" . $_POST["rangoNotas"] . ",
		conbol_mostrar_observaciones_generales=" . $_POST["observacionGeneral"] . ", 
		conbol_mostrar_nota_comportamiento=" . $_POST["notaComportamiento"] . ",
		conbol_mostrar_firmas=" . $_POST["firmas"] . ",
		conbol_calcular_nota=" . $_POST["notas"] . ", 
		conbol_banner_encabezado=" . $archivo . ",
		conbol_ausencias=" . $_POST["ausencias"] . ", 
		conbol_desempeno=" . $_POST["desempeno"] . ",
		conbol_ih=" . $_POST["ih"] . ",
		conbol_acomulado_final=" . $_POST["acomulado"] . ",
		conbol_periodos_anteriores=" . $_POST["periodoAnterior"] . "
	";
	Grados::actualizarConfiguracionBoletin($_POST['id'], $update);
} else {

	Grados::guardarConfiguracionBoletin("conbol_tipo_encabezado, conbol_posicion_logo, conbol_mostrar_areas, conbol_mostrar_materias, conbol_mostrar_indicadores, conbol_mostrar_observaciones_materia, conbol_mostrar_rango_notas, conbol_mostrar_observaciones_generales, conbol_mostrar_nota_comportamiento, conbol_mostrar_firmas, conbol_calcular_nota, conbol_banner_encabezado, conbol_institucion, conbol_year, conbol_ausencias, conbol_desempeno, conbol_ih, conbol_acomulado_final, conbol_periodos_anteriores", [$_POST["encabezado"], $_POST["posicionLogo"], $_POST["areas"], $_POST["materias"], $_POST["indicadores"], $_POST["observaciones"], $_POST["rangoNotas"], $_POST["observacionGeneral"], $_POST["notaComportamiento"], $_POST["firmas"], $_POST["notas"], $archivo, $_SESSION["idInstitucion"], $_SESSION["bd"], $_POST["ausencias"], $_POST["desempeno"], $_POST["ih"], $_POST["acomulado"], $_POST["periodoAnterior"]]);
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cursos-configurar-boletin.php";</script>';
exit();