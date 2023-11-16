<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0111';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("TAR");

$archivoSubido = new Archivos;

$archivo = '';
$pesoMB = '';
if(!empty($_FILES['file']['name'])){
	$nombreInputFile = 'file';
	$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
	$explode=explode(".", $_FILES['file']['name']);
	$extension = end($explode);
	$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
	$destino = ROOT_PATH."/main-app/files/tareas";
	@unlink($destino."/".$archivo);
	$archivoSubido->subirArchivo($destino, $archivo, $nombreInputFile); 
	$pesoMB = round($_FILES['file']['size']/1048576,2);
}

if(empty($_POST["retrasos"]) || (!empty($_POST["retrasos"]) && $_POST["retrasos"]!=1)) $_POST["retrasos"]='0';

try{
	mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_tareas(tar_id, tar_titulo, tar_descripcion, tar_id_carga, tar_periodo, tar_estado, tar_fecha_disponible, tar_fecha_entrega, tar_impedir_retrasos, tar_archivo, tar_peso1, institucion, year)
	VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$_POST["titulo"])."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$cargaConsultaActual."', '".$periodoConsultaActual."', 1, '".$_POST["desde"]."', '".$_POST["hasta"]."', '".$_POST["retrasos"]."', '".$archivo."', '".$pesoMB."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo base64_encode($codigo);
exit();