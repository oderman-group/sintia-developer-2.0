<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0126';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

require_once(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Cronograma.php");
require_once(ROOT_PATH."/main-app/class/Clases.php");

//Importar indicadores
if(!empty($_POST["indicadores"]) and empty($_POST["calificaciones"])){

	try{
		mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga
		WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Importar indicadores de carga: ".$cargaConsultaActual.", del P: ".$_POST["periodoImportar"]." al P: ".$periodoConsultaActual."'
		WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	//Consultamos los indicadores a importar
	try{
		$indImpConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
		INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
		WHERE ipc.ipc_carga='".$_POST["cargaImportar"]."' AND ipc.ipc_periodo='".$_POST["periodoImportar"]."' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	$datosInsert = '';

	while($indImpDatos = mysqli_fetch_array($indImpConsulta, MYSQLI_BOTH)){
		$idRegInd = $indImpDatos['ind_id'];

		//Si el indicador NO es de los obligatorios lo REcreamos.
		if($indImpDatos['ind_obligatorio']==0){
			$idRegInd=Utilidades::generateCode("IND");
			try{
				mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores(ind_id, ind_nombre, ind_periodo, ind_carga, ind_publico, institucion, year)VALUES('".$idRegInd."', '".mysqli_real_escape_string($conexion,$indImpDatos['ind_nombre'])."', '".$periodoConsultaActual."', '".$cargaConsultaActual."', '".$indImpDatos['ind_publico']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}

		$copiado = 0;
		if($indImpDatos['ipc_copiado']!=0) $copiado = $indImpDatos['ipc_copiado'];
		$codigo=Utilidades::generateCode("IPC");

		$datosInsert .="('".$codigo."', '".$cargaConsultaActual."', '".$idRegInd."', '".$indImpDatos['ipc_valor']."', '".$periodoConsultaActual."', 1, '".$copiado."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";	
	}

	if(!empty($datosInsert)){
		$datosInsert = substr($datosInsert,0,-1);
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_carga(ipc_id, ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_copiado, institucion, year) VALUES $datosInsert");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
	$ULR = 'indicadores.php';
}

//Importar calificaciones y los indicadores también porque están realacionados.
if(!empty($_POST["calificaciones"])){
	try{
		mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_indicadores_carga
		WHERE ipc_carga='".$cargaConsultaActual."' AND ipc_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividades SET act_estado=0, act_fecha_eliminacion=now(), act_motivo_eliminacion='Importar de calificaciones de carga: ".$cargaConsultaActual.", del P: ".$_POST["periodoImportar"]." al P: ".$periodoConsultaActual."'
		WHERE act_id_carga='".$cargaConsultaActual."' AND act_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	//Consultamos los indicadores a importar
	try{
		$indImpConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_indicadores_carga ipc
		INNER JOIN ".BD_ACADEMICA.".academico_indicadores ai ON ai.ind_id=ipc.ipc_indicador AND ai.institucion={$config['conf_id_institucion']} AND ai.year={$_SESSION["bd"]}
		WHERE ipc.ipc_carga='".$_POST["cargaImportar"]."' AND ipc.ipc_periodo='".$_POST["periodoImportar"]."' AND ipc.institucion={$config['conf_id_institucion']} AND ipc.year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	$datosInsertInd = '';
	while($indImpDatos = mysqli_fetch_array($indImpConsulta, MYSQLI_BOTH)){
		$idRegInd = $indImpDatos['ind_id'];

		//Si el indicador NO es de los obligatorios lo REcreamos.
		if($indImpDatos['ind_obligatorio']==0){
			$idRegInd=Utilidades::generateCode("IND");
			try{
				mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores(ind_id, ind_nombre, ind_periodo, ind_carga, ind_publico, institucion, year)VALUES('".$idRegInd."', '".mysqli_real_escape_string($conexion,$indImpDatos['ind_nombre'])."', '".$periodoConsultaActual."', '".$cargaConsultaActual."', '".$indImpDatos['ind_publico']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}

		$copiado = 0;
		if($indImpDatos['ipc_copiado']!=0) $copiado = $indImpDatos['ipc_copiado'];
		$codigo=Utilidades::generateCode("IPC");

		$datosInsertInd .="('".$codigo."', '".$cargaConsultaActual."', '".$idRegInd."', '".$indImpDatos['ipc_valor']."', '".$periodoConsultaActual."', 1, '".$copiado."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";

		//Consultamos las calificaciones del indicador a Importar
		try{
			$calImpConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividades
			WHERE act_id_carga='".$_POST["cargaImportar"]."' AND act_periodo='".$_POST["periodoImportar"]."' AND act_id_tipo='".$indImpDatos['ind_id']."' AND act_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}

		$datosInsert = '';
		while($calImpDatos = mysqli_fetch_array($calImpConsulta, MYSQLI_BOTH)){
			$codigoACT=null;

			$datosInsert .="('".$codigoACT."', '".mysqli_real_escape_string($conexion,$calImpDatos['act_descripcion'])."', '".$calImpDatos['act_fecha']."', '".$calImpDatos['act_valor']."', '".$idRegInd."', '".$cargaConsultaActual."', 0, now(), 1, '".$periodoConsultaActual."','".$calImpDatos['act_compartir']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";

		}

		

		if(!empty($datosInsert)){
			$datosInsert = substr($datosInsert,0,-1);
			try{
				mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividades(act_id, act_descripcion, act_fecha, act_valor, act_id_tipo, act_id_carga, act_registrada, act_fecha_creacion, act_estado, act_periodo, act_compartir, institucion, year) VALUES $datosInsert");
			} catch (Exception $e) {
				include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
			}
		}
	}		

	if(!empty($datosInsertInd)){
		$datosInsertInd = substr($datosInsertInd,0,-1);
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_indicadores_carga(ipc_id, ipc_carga, ipc_indicador, ipc_valor, ipc_periodo, ipc_creado, ipc_copiado, institucion, year) VALUES $datosInsertInd");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
	$ULR = 'calificaciones.php';
}

//Importar clases
if(!empty($_POST["clases"])){	
	$calImpConsulta = Clases::eliminarClasesCargas($conexion, $config, $cargaConsultaActual, $periodoConsultaActual);

	//Consultamos las clases a Importar
	$calImpConsulta = Clases::traerClasesCargaPeriodo($conexion, $config, $_POST["cargaImportar"], $_POST["periodoImportar"]);

	$datosInsert = '';
	while($calImpDatos = mysqli_fetch_array($calImpConsulta, MYSQLI_BOTH)){
		$codigoCLS=Utilidades::generateCode("CLS");
		$datosInsert .="('".$codigoCLS."', '".$calImpDatos['cls_tema']."', now(), '".$cargaConsultaActual."', 0, now(), 1, '".$periodoConsultaActual."', '".$calImpDatos['cls_archivo']."', '".$calImpDatos['cls_video']."', '".$calImpDatos['cls_video_url']."', '".$calImpDatos['cls_descripcion']."', '".$calImpDatos['cls_archivo2']."', '".$calImpDatos['cls_archivo3']."', '".$calImpDatos['cls_nombre_archivo1']."', '".$calImpDatos['cls_nombre_archivo2']."', '".$calImpDatos['cls_nombre_archivo3']."', '".$calImpDatos['cls_disponible']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
	}

	if(!empty($datosInsert)){
		$datosInsert = substr($datosInsert,0,-1);
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_clases(cls_id, cls_tema, cls_fecha, cls_id_carga, cls_registrada, cls_fecha_creacion, cls_estado, cls_periodo, cls_archivo, cls_video, cls_video_url, cls_descripcion, cls_archivo2, cls_archivo3, cls_nombre_archivo1, cls_nombre_archivo2, cls_nombre_archivo3, cls_disponible, institucion, year) VALUES $datosInsert");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
	$ULR = 'clases.php';
}



//Importar actividades

if(!empty($_POST["actividades"])){		
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_tareas SET tar_estado=0
		WHERE tar_id_carga='".$cargaConsultaActual."' AND tar_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	//Consultamos las actividades a Importar
	try{
		$calImpConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_tareas
		WHERE tar_id_carga='".$_POST["cargaImportar"]."' AND tar_periodo='".$_POST["periodoImportar"]."' AND tar_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	$datosInsert = '';
	while($calImpDatos = mysqli_fetch_array($calImpConsulta, MYSQLI_BOTH)){
		$codigo=Utilidades::generateCode("TAR");
		$datosInsert .="('".$codigo."', '".$calImpDatos['tar_titulo']."', '".$calImpDatos['tar_descripcion']."', '".$cargaConsultaActual."', '".$calImpDatos['tar_fecha_disponible']."', '".$calImpDatos['tar_fecha_entrega']."', '".$calImpDatos['tar_archivo']."', '".$calImpDatos['tar_impedir_retrasos']."', '".$periodoConsultaActual."', 1, '".$calImpDatos['tar_archivo2']."', '".$calImpDatos['ar_archivo3']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";	
	}

	if(!empty($datosInsert)){
		$datosInsert = substr($datosInsert,0,-1);
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_tareas(tar_id, tar_titulo, tar_descripcion, tar_id_carga, tar_fecha_disponible, tar_fecha_entrega, tar_archivo, tar_impedir_retrasos, tar_periodo, tar_estado, tar_archivo2, ar_archivo3, institucion, year)VALUES $datosInsert");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
	$ULR = 'actividades.php';
}

//Importar foros
if(!empty($_POST["foros"])){		
	try{
		mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_actividad_foro SET foro_estado=0
		WHERE foro_id_carga='".$cargaConsultaActual."' AND foro_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
	//Consultamos las foros a Importar
	try{
		$calImpConsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_actividad_foro
		WHERE foro_id_carga='".$_POST["cargaImportar"]."' AND foro_periodo='".$_POST["periodoImportar"]."' AND foro_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}

	$datosInsert = '';
	while($calImpDatos = mysqli_fetch_array($calImpConsulta, MYSQLI_BOTH)){
		$codigo=Utilidades::generateCode("FORO");
		$datosInsert .="('".$codigo."', '".$calImpDatos['foro_nombre']."', '".$calImpDatos['foro_descripcion']."', '".$cargaConsultaActual."', '".$periodoConsultaActual."', 1, {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
	}

	if(!empty($datosInsert)){
		$datosInsert = substr($datosInsert,0,-1);
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_actividad_foro(foro_id, foro_nombre, foro_descripcion, foro_id_carga, foro_periodo, foro_estado, institucion, year)VALUES $datosInsert");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
	$ULR = 'foros.php';
}
//Importar cronograma
if(!empty($_POST["cronograma"])){		
	//Consultamos la información del cronograma a Importar
	$calImpConsulta = Cronograma::traerDatosCompletosCronograma($conexion, $config, $_POST["cargaImportar"], $_POST["periodoImportar"]);

	$datosInsert = '';
	while($calImpDatos = mysqli_fetch_array($calImpConsulta, MYSQLI_BOTH)){
		$idInsercion=Utilidades::generateCode("CRO");
		$datosInsert .="('" .$idInsercion . "', '".$calImpDatos['cro_tema']."', '".$calImpDatos['cro_fecha']."', '".$cargaConsultaActual."', '".$calImpDatos['cro_recursos']."', '".$periodoConsultaActual."', '".$calImpDatos['cro_color']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]}),";
	}

	if(!empty($datosInsert)){
		$datosInsert = substr($datosInsert,0,-1);
		try{
			mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_cronograma(cro_id, cro_tema, cro_fecha, cro_id_carga, cro_recursos, cro_periodo, cro_color, institucion, year)VALUES $datosInsert");
		} catch (Exception $e) {
			include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
		}
	}
	$ULR = 'cronograma-calendario.php';
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="'.$ULR.'?success=SC_GN_2&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();