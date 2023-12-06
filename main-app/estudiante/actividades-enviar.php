<?php
include("session.php");
include("verificar-usuario.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'ES0058';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");

$archivoSubido = new Archivos;

try{
	$fechas = mysqli_fetch_array(mysqli_query($conexion, "SELECT DATEDIFF(tar_fecha_disponible, now()), DATEDIFF(tar_fecha_entrega, now()), tar_fecha_entrega, tar_impedir_retrasos FROM ".BD_ACADEMICA.".academico_actividad_tareas 
	WHERE tar_id='".$_POST["idR"]."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"), MYSQLI_BOTH);
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

if($fechas[1]<0 and $fechas[3]==1){
	
	include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=207&fechaH='.$fechas[2].'&diasP='.$fechas[1].'";</script>';
	exit();
}

$destino = ROOT_PATH."/main-app/files/tareas-entregadas";
try{
	$num = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_estudiante='".$datosEstudianteActual['mat_id']."' AND ent_id_actividad='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"));
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}	

if($num == 0){
	require_once(ROOT_PATH."/main-app/class/Utilidades.php");
	$codigo=Utilidades::generateCode("ENT");

	$archivo = "";
	$pesoMB1 = "";
	if(!empty($_FILES['file']['name'])){
		$nombreInputFile = 'file';
		$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
		$explode=explode(".", $_FILES['file']['name']);
		$extension = end($explode);
		$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
		@unlink($destino."/".$archivo);
		$archivoSubido->subirArchivo($destino, $archivo, $nombreInputFile);
		$pesoMB1 = round($_FILES['file']['size']/1048576,2);
	}

	$archivo2 = "";
	$pesoMB2 = "";
	if(!empty($_FILES['file2']['name'])){
		$nombreInputFile = 'file2';
		$archivoSubido->validarArchivo($_FILES['file2']['size'], $_FILES['file2']['name']);
		$explode2=explode(".", $_FILES['file2']['name']);
		$extension2 = end($explode2);
		$archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
		@unlink($destino."/".$archivo2);
		$archivoSubido->subirArchivo($destino, $archivo2, $nombreInputFile);
		$pesoMB2 = round($_FILES['file2']['size']/1048576,2);
	}

	$archivo3 = "";
	$pesoMB3 = "";
	if(!empty($_FILES['file3']['name'])){
		$nombreInputFile = 'file3';
		$archivoSubido->validarArchivo($_FILES['file3']['size'], $_FILES['file3']['name']);
		$explode3=explode(".", $_FILES['file3']['name']);
		$extension3 = end($explode3);
		$archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
		@unlink($destino."/".$archivo3);
		$archivoSubido->subirArchivo($destino, $archivo3, $nombreInputFile); 
		$pesoMB3 = round($_FILES['file3']['size']/1048576,2);
	}

	try{
		mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_actividad_tareas_entregas WHERE ent_id_estudiante='".$datosEstudianteActual['mat_id']."' AND ent_id_actividad='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_tareas_entregas (ent_id, ent_id_estudiante, ent_id_actividad, ent_archivo, ent_fecha, ent_comentario, ent_archivo2, ent_archivo3, ent_peso1, ent_peso2, ent_peso3, institucion, year) VALUES('".$codigo."', '".$datosEstudianteActual['mat_id']."', '".$_POST["idR"]."', '".$archivo."', now(), '".mysqli_real_escape_string($conexion,$_POST["comentario"])."', '".$archivo2."', '".$archivo3."', '".$pesoMB1."', '".$pesoMB2."', '".$pesoMB3."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}else{

	if(!empty($_FILES['file']['name'])){
		$nombreInputFile = 'file';
		$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
		$explode=explode(".", $_FILES['file']['name']);
		$extension = end($explode);
		$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file1_').".".$extension;
		@unlink($destino."/".$archivo);
		$archivoSubido->subirArchivo($destino, $archivo, $nombreInputFile);

		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_archivo='".$archivo."' WHERE ent_id_estudiante='".$datosEstudianteActual['mat_id']."' AND ent_id_actividad='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}

	if(!empty($_FILES['file2']['name'])){
		$nombreInputFile = 'file2';
		$archivoSubido->validarArchivo($_FILES['file2']['size'], $_FILES['file2']['name']);
		$explode2=explode(".", $_FILES['file2']['name']);
		$extension2 = end($explode2);
		$archivo2 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file2_').".".$extension2;
		@unlink($destino."/".$archivo2);
		$archivoSubido->subirArchivo($destino, $archivo2, $nombreInputFile);
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_archivo2='".$archivo2."' WHERE ent_id_estudiante='".$datosEstudianteActual['mat_id']."' AND ent_id_actividad='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}

	if(!empty($_FILES['file3']['name'])){
		$nombreInputFile = 'file3';
		$archivoSubido->validarArchivo($_FILES['file3']['size'], $_FILES['file3']['name']);
		$explode3=explode(".", $_FILES['file3']['name']);
		$extension3 = end($explode3);
		$archivo3 = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file3_').".".$extension3;
		@unlink($destino."/".$archivo3);
		$archivoSubido->subirArchivo($destino, $archivo3, $nombreInputFile);
		try{
			mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_archivo3='".$archivo3."' WHERE ent_id_estudiante='".$datosEstudianteActual['mat_id']."' AND ent_id_actividad='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}

	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas_entregas SET ent_comentario='".mysqli_real_escape_string($conexion,$_POST["comentario"])."' WHERE ent_id_estudiante='".$datosEstudianteActual['mat_id']."' AND ent_id_actividad='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=107";</script>';
exit();