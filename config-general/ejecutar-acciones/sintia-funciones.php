<?php
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/constantes.php");
include(ROOT_PATH."/conexion-datos.php");
$conexion = mysqli_connect($servidorConexion, $usuarioConexion, $claveConexion, $baseDatosServicios);

//config
$tablaDestino = 'general_resultados';
$camposDestino = '
resg_id_pregunta,
resg_id_respuesta,
resg_id_estudiante,
resg_id_asignacion,
resg_institucion,
resg_year
';

$tablaOrigen = 'general_resultados';

//consulta a instituciones activas
$consultaInstituciones = mysqli_query($conexion, "SELECT * FROM instituciones
WHERE ins_estado=1
");
$totalCompleto = 0;
while($datosInstitucion = mysqli_fetch_array($consultaInstituciones, MYSQLI_BOTH)){
	$yearArray = explode(",", $datosInstitucion['ins_years']);
	$yearStart = $yearArray[0];
	$yearEnd = $yearArray[1];
	
	while($yearStart <= $yearEnd){
		$camposOrigen = '
		resg_id_pregunta,
resg_id_respuesta,
resg_id_estudiante,
resg_id_asignacion,
		'.$datosInstitucion['ins_id'].',
		'.$yearStart;

		$getDataQuery = mysqli_query($conexion, "INSERT INTO $baseDatosServicios.".$tablaDestino."(".$camposDestino.") SELECT ".$camposOrigen." FROM ".$datosInstitucion['ins_bd']."_".$yearStart.".".$tablaOrigen);
		$totalRegistros = mysqli_affected_rows($conexion);
		$totalCompleto += $totalRegistros;

		echo "Se completó correctamente la inserción de <b>".$totalRegistros."</b> registros desde <b>".$tablaOrigen." ".$datosInstitucion['ins_bd']."_".$yearStart."</b><br>";

		$yearStart ++;
	}
}

echo "En total fueron <b>$totalCompleto</b> registros";