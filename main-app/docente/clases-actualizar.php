<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0112';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$archivoSubido = new Archivos;

//Archivos
$destino = "../files/clases";
if(!empty($_FILES['file']['name'])){
	$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
	$explode=explode(".", $_FILES['file']['name']);
	$extension = end($explode);
	$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
	@unlink($destino."/".$archivo);
	move_uploaded_file($_FILES['file']['tmp_name'], $destino ."/".$archivo);
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_archivo='".$archivo."' WHERE cls_id='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

if(!empty($_FILES['file2']['name'])){
	$archivoSubido->validarArchivo($_FILES['file2']['size'], $_FILES['file2']['name']);
	$explode=explode(".", $_FILES['file2']['name']);
	$extension = end($explode);
	$archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
	@unlink($destino."/".$archivo2);
	move_uploaded_file($_FILES['file2']['tmp_name'], $destino ."/".$archivo2);
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_archivo2='".$archivo2."' WHERE cls_id='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

if(!empty($_FILES['file3']['name'])){
	$archivoSubido->validarArchivo($_FILES['file3']['size'], $_FILES['file3']['name']);
	$explode=explode(".", $_FILES['file3']['name']);
	$extension = end($explode);
	$archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
	@unlink($destino."/".$archivo3);
	move_uploaded_file($_FILES['file3']['tmp_name'], $destino ."/".$archivo3);
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_archivo3='".$archivo3."' WHERE cls_id='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include("../compartido/error-catch-to-report.php");
	}
}

$findme   = '?v=';
$pos = strpos($_POST["video"], $findme) + 3;
$video = substr($_POST["video"],$pos,11);

$disponible=0;
if($_POST["disponible"]==1) $disponible=1;

$date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));

try{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_clases SET cls_tema='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', cls_fecha='".$date."', cls_video='".$video."', cls_video_url='".$_POST["video"]."', cls_descripcion='".mysqli_real_escape_string($conexion,$_POST["descripcion"])."', cls_nombre_archivo1='".$_POST["archivo1"]."', cls_nombre_archivo2='".$_POST["archivo2"]."', cls_nombre_archivo3='".$_POST["archivo3"]."', cls_disponible='".$disponible."', cls_hipervinculo='".$_POST["vinculo"]."', cls_unidad='".$_POST["unidad"]."'
	WHERE cls_id='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();