<?php
$carga="";
if(!empty($_GET["carga"])){ $carga=base64_decode($_GET["carga"]);}
$periodo="";
if(!empty($_GET["periodo"])){ $periodo=base64_decode($_GET["periodo"]);}

if(!isset($_GET["carga"]) or !isset($_GET["periodo"]) or !is_numeric($carga) or !is_numeric($periodo)){
	if(!empty($_COOKIE["cargaE"]) and !empty($_COOKIE["periodoE"])){
		$cargaConsultaActual = $_COOKIE["cargaE"];
		$periodoConsultaActual = $_COOKIE["periodoE"];
	}else{
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
			exit();
	}
}else{
	$cargaConsultaActual = $carga;
	$periodoConsultaActual = $periodo;
}

$cargaHconsulta = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas_acceso 
WHERE carpa_id_carga='".$cargaConsultaActual."' AND carpa_id_estudiante='".$datosEstudianteActual['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");

$cargaHnum = mysqli_num_rows($cargaHconsulta);
if($cargaHnum==0){
	mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_cargas_acceso(carpa_id_carga, carpa_id_estudiante, carpa_primer_acceso, carpa_ultimo_acceso, carpa_cantidad, institucion, year)
	VALUES('".$cargaConsultaActual."', '".$datosEstudianteActual['mat_id']."', now(), now(), 1, {$config['conf_id_institucion']}, {$_SESSION["bd"]})
	");
}else{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas_acceso SET carpa_ultimo_acceso=now(), carpa_cantidad=carpa_cantidad+1
	WHERE carpa_id_carga='".$cargaConsultaActual."' AND carpa_id_estudiante='".$datosEstudianteActual['mat_id']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
}

$consultaCargaActual = mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas car 
INNER JOIN ".BD_ACADEMICA.".academico_materias am ON am.mat_id=car_materia AND am.institucion={$config['conf_id_institucion']} AND am.year={$_SESSION["bd"]}
INNER JOIN ".BD_GENERAL.".usuarios uss ON uss_id=car_docente AND uss.institucion={$config['conf_id_institucion']} AND uss.year={$_SESSION["bd"]}
LEFT JOIN ".$baseDatosServicios.".mediatecnica_matriculas_cursos ON matcur_id_matricula='".$datosEstudianteActual['mat_id']."'
WHERE car_id='".$cargaConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]} AND (car_curso='".$datosEstudianteActual['mat_grado']."' OR car_curso=matcur_id_curso) AND (car_grupo='".$datosEstudianteActual['mat_grupo']."' OR car_grupo=matcur_id_grupo) AND car_activa=1");

$numCargaActual = mysqli_num_rows($consultaCargaActual);
$datosCargaActual = mysqli_fetch_array($consultaCargaActual, MYSQLI_BOTH);
if($numCargaActual==0)
{
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
	exit();		
}else{
	//Verificar si el estudiante está matriculado en cursos de extensión o complementarios
	if($datosCargaActual['car_curso_extension']==1){
		$cursoExtensionNum = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM ".BD_ACADEMICA.".academico_cargas_estudiantes WHERE carpest_carga='".$datosCargaActual['car_id']."' AND carpest_estudiante='".$datosEstudianteActual['mat_id']."' AND carpest_estado=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}"));
		if($cursoExtensionNum==0){
			echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100&cursoext=1";</script>';
			exit();	
		}
	}
}

if($config['conf_activar_encuesta']==1){
	$respuesta = mysqli_num_rows(mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_encuestas 
	WHERE genc_estudiante='".$datosEstudianteActual['mat_id']."' AND genc_institucion={$config['conf_id_institucion']} AND genc_year={$_SESSION["bd"]}"));
	if($respuesta==0 and $datosEstudianteActual['mat_grado']!=11){
		echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=214";</script>';
		exit();	
	}
}
?>
