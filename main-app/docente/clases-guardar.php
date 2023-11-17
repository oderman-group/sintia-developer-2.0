<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0116';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("CLS");

$archivoSubido = new Archivos;

//Archivos
$archivo = '';
$destino = ROOT_PATH."/main-app/files/clases";
if(!empty($_FILES['file']['name'])){
	$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
	$explode=explode(".", $_FILES['file']['name']);
	$extension = end($explode);
	$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
	@unlink($destino."/".$archivo);
	move_uploaded_file($_FILES['file']['tmp_name'], $destino ."/".$archivo);
}

$archivo2 = '';
if(!empty($_FILES['file2']['name'])){
	$archivoSubido->validarArchivo($_FILES['file2']['size'], $_FILES['file2']['name']);
	$explode=explode(".", $_FILES['file2']['name']);
	$extension = end($explode);
	$archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
	@unlink($destino."/".$archivo2);
	move_uploaded_file($_FILES['file2']['tmp_name'], $destino ."/".$archivo2);
}

$archivo3 = '';
if(!empty($_FILES['file3']['name'])){
	$archivoSubido->validarArchivo($_FILES['file3']['size'], $_FILES['file3']['name']);
	$explode=explode(".", $_FILES['file3']['name']);
	$extension = end($explode);
	$archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
	@unlink($destino."/".$archivo3);
	move_uploaded_file($_FILES['file3']['tmp_name'], $destino ."/".$archivo3);
}

$findme   = '?v=';
$pos = strpos($_POST["video"], $findme) + 3;
$video = substr($_POST["video"],$pos,11);

if(empty($_POST["bancoDatos"]) || $_POST["bancoDatos"]==0){
	$date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));
	$disponible=0;
	if($_POST["disponible"]==1) $disponible=1;

	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_clases(cls_id, cls_tema, cls_fecha, cls_id_carga, cls_estado, cls_periodo, cls_video, cls_video_url, cls_archivo, cls_archivo2, cls_archivo3, cls_nombre_archivo1, cls_nombre_archivo2, cls_nombre_archivo3, cls_descripcion, cls_disponible, cls_meeting, cls_hipervinculo,cls_unidad, institucion, year)"." VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$date."', '".$cargaConsultaActual."', 1, '".$periodoConsultaActual."', '".$video."', '".$_POST["video"]."', '".$archivo."', '".$archivo2."', '".$archivo3."', '".$_POST["archivo1"]."', '".$_POST["archivo2"]."', '".$_POST["archivo3"]."', '".mysqli_real_escape_string($conexion,$_POST["descripcion"])."', '".$disponible."', '".$_POST["idMeeting"]."', '".$_POST["vinculo"]."', '".$_POST["unidad"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo base64_encode($codigo);
exit();