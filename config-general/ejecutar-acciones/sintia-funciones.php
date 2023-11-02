<?php
require_once($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
//include(ROOT_PATH."/conexion-datos.php");
$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

//config
$tablaDestino = 'matriculas_aspectos';
$camposDestino = '
mata_id,
mata_estudiante,
mata_aspecto_academico,
mata_aspecto_disciplinario,
mata_usuario,
mata_fecha,
mata_fecha_evento,
mata_aspectos_positivos,
mata_aspectos_mejorar,
mata_tratamiento,
mata_descripcion,
mata_periodo,
mata_aprobacion_acudiente,
mata_aprobacion_acudiente_fecha,
institucion,
year
';

mysqli_query($conexion, "TRUNCATE TABLE {$baseDatosServicios}.{$tablaDestino}");


$tablaOrigen = 'matriculas_aspectos';

//consulta a instituciones activas
$consultaInstituciones = mysqli_query($conexion, "SELECT * FROM instituciones
WHERE ins_estado=1 AND ins_enviroment='".ENVIROMENT."'
");
$totalCompleto = 0;
while($datosInstitucion = mysqli_fetch_array($consultaInstituciones, MYSQLI_BOTH)){
	$yearArray = explode(",", $datosInstitucion['ins_years']);
	$yearStart = $yearArray[0];
	$yearEnd = $yearArray[1];
	
	while($yearStart <= $yearEnd){
		$camposOrigen = '
		mata_id,
mata_estudiante,
mata_aspecto_academico,
mata_aspecto_disciplinario,
mata_usuario,
mata_fecha,
mata_fecha_evento,
mata_aspectos_positivos,
mata_aspectos_mejorar,
mata_tratamiento,
mata_descripcion,
mata_periodo,
mata_aprobacion_acudiente,
mata_aprobacion_acudiente_fecha,
		'.$datosInstitucion['ins_id'].',
		'.$yearStart
		;

		$getDataQuery = mysqli_query($conexion, "INSERT INTO $baseDatosServicios.".$tablaDestino."(".$camposDestino.") SELECT ".$camposOrigen." FROM ".$datosInstitucion['ins_bd']."_".$yearStart.".".$tablaOrigen);
		$totalRegistros = mysqli_affected_rows($conexion);
		$totalCompleto += $totalRegistros;

		echo "Se completó correctamente la inserción de <b>".$totalRegistros."</b> registros desde <b>".$tablaOrigen." ".$datosInstitucion['ins_bd']."_".$yearStart."</b><br>";

		$yearStart ++;
	}
}

echo "En total fueron <b>$totalCompleto</b> registros";