<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0123';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
try{
	mysqli_query($conexion, "UPDATE academico_actividad_preguntas SET preg_descripcion='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', preg_valor='".$_POST["valor"]."' WHERE preg_id='".$_POST["idR"]."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

//Archivos para evaluaciones
$destino = ROOT_PATH."/main-app/files/evaluaciones";
if(!empty($_FILES['file']['name'])){
	$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
	$explode=explode(".", $_FILES['file']['name']);
	$extension = end($explode);
	$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_eva_').".".$extension;
	@unlink($destino."/".$archivo);
	move_uploaded_file($_FILES['file']['tmp_name'], $destino ."/".$archivo);
	try{
		mysqli_query($conexion, "UPDATE academico_actividad_preguntas SET preg_archivo='".$archivo."' WHERE preg_id='".$_POST["idR"]."'");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-preguntas.php?idE='.base64_encode($_POST["idE"]).'#pregunta'.base64_encode($_POST["idR"]).'";</script>';
exit();