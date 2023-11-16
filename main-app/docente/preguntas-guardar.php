<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0121';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("PRE");

if(empty($_POST["bancoDatos"]) || $_POST["bancoDatos"]==0){
	//Archivos para evaluaciones
	$destino = ROOT_PATH."/main-app/files/evaluaciones";
	$archivo = "";
	if(!empty($_FILES['file']['name'])){
		$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
		$explode=explode(".", $_FILES['file']['name']);
		$extension = end($explode);
		$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_eva_').".".$extension;
		@unlink($destino."/".$archivo);
		move_uploaded_file($_FILES['file']['tmp_name'], $destino ."/".$archivo);
	}
	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_preguntas(preg_id, preg_descripcion, preg_valor, preg_id_carga, preg_tipo_pregunta, preg_archivo, institucion, year)VALUES('".$codigo."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."','".$_POST["valor"]."','".$_COOKIE["carga"]."', '".$_POST["opcionR"]."', '".$archivo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "INSERT INTO academico_actividad_evaluacion_preguntas(evp_id_evaluacion, evp_id_pregunta)VALUES('".$_POST["idE"]."','".$codigo."')");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	if($_POST["opcionR"]==1){
		$cont=1;
		$datosInsert = '';
		while($cont<=4){
			$codigoR=Utilidades::generateCode("RES");
			if(!empty(trim($_POST["r$cont"]))){
				if(empty($_POST["c$cont"])){$_POST["c$cont"]=0;}
				$datosInsert .="('".$codigoR."', '".mysqli_real_escape_string($conexion,$_POST["r$cont"])."','".$_POST["c$cont"]."','".$codigo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
				$cont++;
			}
		}

		if(!empty($datosInsert)){
			$datosInsert = substr($datosInsert,0,-1);
			try{
				mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_respuestas(resp_id, resp_descripcion, resp_correcta, resp_id_pregunta, institucion, year)VALUES $datosInsert");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}
	}

	if($_POST["opcionR"]==2){
		$cont=1;
		$datosInsert = '';
		while($cont<=2){
			$codigoR=Utilidades::generateCode("RES");
			if(!empty(trim($_POST["rv$cont"]))){
				if(empty($_POST["cv$cont"])){$_POST["cv$cont"]=0;}
				$datosInsert .="('".$codigoR."', '".mysqli_real_escape_string($conexion,$_POST["rv$cont"])."','".$_POST["cv$cont"]."','".$codigo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
				$cont++;
			}
		}

		if(!empty($datosInsert)){
			$datosInsert = substr($datosInsert,0,-1);
			try{
				mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_respuestas(resp_id, resp_descripcion, resp_correcta, resp_id_pregunta, institucion, year)VALUES $datosInsert");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}
	}

	if($_POST["opcionR"]==3){
		$codigoR=Utilidades::generateCode("RES");
		$datosInsert .="('".$codigoR."', 'Adjuntar un archivo','0','".$codigo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
		if(!empty($datosInsert)){
			$datosInsert = substr($datosInsert,0,-1);
			try{
				mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_respuestas(resp_id, resp_descripcion, resp_correcta, resp_id_pregunta, institucion, year)VALUES $datosInsert");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}
	}
}else{
	try{
		$consultaPreguntaBD=mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_preguntas WHERE preg_id='".$_POST["bancoDatos"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		$preguntaBD = mysqli_fetch_array($consultaPreguntaBD, MYSQLI_BOTH);
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_preguntas(preg_id, preg_descripcion, preg_valor, preg_id_carga, preg_imagen1, preg_imagen2, preg_tipo_pregunta, preg_archivo, institucion, year)VALUES('".$codigo."', '".$preguntaBD['preg_descripcion']."', '".$preguntaBD['preg_valor']."', '".$cargaConsultaActual."', '".$preguntaBD['preg_imagen1']."', '".$preguntaBD['preg_imagen2']."', '".$preguntaBD['preg_tipo_pregunta']."', '".$preguntaBD['preg_archivo']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	try{
		$respuestasPreguntaConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_respuestas WHERE resp_id_pregunta='".$_POST["bancoDatos"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	while($respuestasPreguntaDatos = mysqli_fetch_array($respuestasPreguntaConsulta, MYSQLI_BOTH)){
		$codigoR=Utilidades::generateCode("RES");
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_respuestas(resp_id, resp_descripcion, resp_correcta, resp_id_pregunta, resp_imagen, institucion, year)VALUES('".$codigoR."', '".$respuestasPreguntaDatos['resp_descripcion']."', '".$respuestasPreguntaDatos['resp_correcta']."', '".$codigo."', '".$respuestasPreguntaDatos['resp_imagen']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}

	try{
		mysqli_query($conexion, "INSERT INTO academico_actividad_evaluacion_preguntas(evp_id_evaluacion, evp_id_pregunta)VALUES('".$_POST["idE"]."', '".$codigo."')");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-preguntas.php?idE='.base64_encode($_POST["idE"]).'#pregunta'.base64_encode($codigo).'";</script>';
exit();