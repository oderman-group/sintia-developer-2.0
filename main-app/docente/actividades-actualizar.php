<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0112';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$archivoSubido = new Archivos;

if(!empty($_FILES['file']['name'])){
	$nombreInputFile = 'file';
	$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
	$explode=explode(".", $_FILES['file']['name']);
	$extension = end($explode);
	$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
	$destino = ROOT_PATH."/main-app/files/tareas";
	@unlink($destino."/".$archivoAnterior);
	$archivoSubido->subirArchivo($destino, $archivo, $nombreInputFile); 
	try{
		mysqli_query($conexion, "UPDATE academico_actividad_tareas SET tar_archivo='".$archivo."' WHERE tar_id='".$_POST["idR"]."'");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

if(empty($_POST["retrasos"]) || $_POST["retrasos"]!=1) $_POST["retrasos"]='0';

try{
	mysqli_query($conexion, "UPDATE academico_actividad_tareas SET tar_titulo='".mysqli_real_escape_string($conexion,$_POST["titulo"])."', tar_descripcion='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', tar_fecha_disponible='".$_POST["desde"]."', tar_fecha_entrega='".$_POST["hasta"]."', tar_impedir_retrasos='".$_POST["retrasos"]."' WHERE tar_id='".$_POST["idR"]."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="actividades.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
exit();