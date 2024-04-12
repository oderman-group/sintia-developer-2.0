<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0121';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Evaluaciones.php");

$archivoSubido = new Archivos;

if(empty($_POST["bancoDatos"]) || $_POST["bancoDatos"]==0){
	
	
	$codigo = Evaluaciones::guardarPreguntasEvaluacion($conexion, $config, $_POST, $_FILES,false);	
	if($_POST["opcionR"]==1){
		$cont=1;
		$datosInsert = '';
		while($cont<=4){
			$codigoR=Utilidades::generateCode("RES");
			if(!empty(trim($_POST["r$cont"]))){
				if(empty($_POST["c$cont"])){$_POST["c$cont"]=0;}
				$datosInsert .="('".$codigoR."', '".mysqli_real_escape_string($conexion,$_POST["r$cont"])."','".$_POST["c$cont"]."','".$codigo."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
			}
			$cont++;
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
			}
			$cont++;
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
	Evaluaciones::guardarRelacionPreguntaEvaluacion($conexion, $conexionPDO, $config, $codigo, $_POST);
}else{
	$preguntaBD = Evaluaciones::traerDatosPreguntas($conexion, $config, $_POST["bancoDatos"]);

	$codigo = Evaluaciones::guardarPreguntasBDEvaluacion($conexion, $config, $preguntaBD, $cargaConsultaActual);

	$respuestasPreguntaConsulta = Evaluaciones::traerRespuestaPregunta($conexion, $config, $_POST["bancoDatos"]);

	while($respuestasPreguntaDatos = mysqli_fetch_array($respuestasPreguntaConsulta, MYSQLI_BOTH)){

		Evaluaciones::guardarRespuestaBD($conexion, $config, $respuestasPreguntaDatos, $codigo);
		
	}

	Evaluaciones::guardarRelacionPreguntaEvaluacion($conexion, $conexionPDO, $config, $codigo, $_POST);
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones-preguntas.php?idE='.base64_encode($_POST["idE"]).'#pregunta'.base64_encode($codigo).'";</script>';
exit();