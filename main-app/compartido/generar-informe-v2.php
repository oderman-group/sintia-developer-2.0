<?php
session_start();

if (empty($_GET["grado"]) || empty($_GET["grupo"]) || empty($_GET["carga"]) || empty($_GET["periodo"]) || empty($_GET["tipoGrado"])) {
?>
	<script language="javascript">
		window.location.href="../docente/page-info.php?idmsg=112";
	</script>
<?php
	exit();
}

$idPaginaInterna = 'CM0006';
include($_SERVER['DOCUMENT_ROOT']."/app-sintia/config-general/config.php");
require_once(ROOT_PATH."/main-app/class/Estudiantes.php");
require_once ROOT_PATH."/main-app/class/Conexion.php";
require_once(ROOT_PATH."/main-app/class/Indicadores.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");
require_once(ROOT_PATH."/main-app/class/Calificaciones.php");
require_once(ROOT_PATH."/main-app/class/Boletin.php");

$conexionPDO = Conexion::newConnection('PDO');

$grado     = base64_decode($_GET["grado"]);
$grupo     = base64_decode($_GET["grupo"]);
$carga     = base64_decode($_GET["carga"]);
$periodo   = base64_decode($_GET["periodo"]);
$tipoGrado = base64_decode($_GET["tipoGrado"]);

if ($config['conf_porcentaje_completo_generar_informe'] == GENERAR_CON_PORCENTAJE_COMPLETO) {
	$consultaListaEstudantesError = Estudiantes::listarEstudiantesNotasFaltantes($carga, $periodo, $tipoGrado);

	//Verificamos que el estudiante no tenga notas faltantes para generar el informe
	if (mysqli_num_rows($consultaListaEstudantesError) > 0) {
?>
		<script language="javascript">
			window.location.href="../docente/page-info.php?idmsg=108&carga=<?=$_GET["carga"];?>&periodo=<?=$_GET["periodo"];?>";
		</script>
<?php		
		exit();
	}
}

$datosCarga = [
	'car_curso' => $grado,
	'car_grupo' => $grupo,
	'gra_tipo'  => $tipoGrado
];

//Consultamos los estudiantes del grado y grupo
$consulta = Estudiantes::listarEstudiantesConInfoBasica($datosCarga);

$contBol = 1;
while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

	//Consultamos si tiene registros en el boletín
	$boletinDatos = Boletin::obtenerNotasBoletin($config, $periodo, $resultado['mat_id'], $carga);

	// Omitimos a los estudiantes que NO tienen registros en la tabla boletín
	if (empty($boletinDatos['bol_id'])) {
		//TODO: Loguear ese error en algun lado y buscar la forma de insertarlo en el boletin si es que tiene registros de notas
		continue;
	}

	//Validar la configuración que omite a los estudiantes que NO tienen el 100% de calificaciones
	//Verificamos si el procentaje actual es menor al mínimo permitido y que el estudiante NO tenga nota registrada previamente
	if (
		$config['conf_porcentaje_completo_generar_informe'] == OMITIR_ESTUDIANTES_CON_PORCENTAJE_INCOMPLETO && 
		$boletinDatos['bol_porcentaje'] < PORCENTAJE_MINIMO_GENERAR_INFORME && 
		empty($boletinDatos['bol_nota'])
	) {
		continue;
	}

	//Vamos a obtener las definitivas por cada indicador y la definitiva general de la asignatura
	$notasPorIndicador = Calificaciones::traerNotasPorIndicador($config, $carga, $resultado['mat_id'], $periodo);
	$sumaNotaIndicador = 0; 

	while ($notInd = mysqli_fetch_array($notasPorIndicador, MYSQLI_BOTH)) {
		$consultaNum = Indicadores::consultaRecuperacionIndicadorPeriodo($config, $notInd[1], $resultado['mat_id'], $carga, $periodo);
		$num         = mysqli_num_rows($consultaNum);

		$sumaNotaIndicador += $notInd[0];

		if ($num == 0) {
			Indicadores::eliminarRecuperacionIndicadorPeriodo($config, $notInd[1], $resultado['mat_id'], $carga, $periodo);				

			Indicadores::guardarRecuperacionIndicador($conexionPDO, $config, $resultado['mat_id'], $carga, $notInd[0], $notInd[1], $periodo, $notInd[2]);
		} else {
			Indicadores::actualizarRecuperacionIndicador($config, $resultado['mat_id'], $carga, $notInd[0], $notInd[1], $periodo, $notInd[2]);
		}
	} 

	$sumaNotaIndicador = round($sumaNotaIndicador, 1);

	if (!empty($boletinDatos['bol_historial_actualizacion']) && $boletinDatos['bol_historial_actualizacion'] != NULL) {
		$actualizacion = json_decode($boletinDatos['bol_historial_actualizacion'], true);
	} else {
		$actualizacion = [];
	}

	$fecha = $boletinDatos['bol_fecha_registro'];

	if (!empty($boletinDatos['bol_ultima_actualizacion']) && $boletinDatos['bol_ultima_actualizacion'] !=NULL ) {
		$fecha = $boletinDatos['bol_ultima_actualizacion'];
	}

	$nuevoArray = [
		"nota_anterior" 		 => round($boletinDatos['bol_nota'], $config['conf_decimales_notas']),
		"fecha_de_actualizacion" => $fecha,
		"porcentaje" 	         => $boletinDatos['bol_porcentaje'],
		"estado"           		 => 'GENERADO',
	];

	$numActualizacion                 = $boletinDatos['bol_actualizaciones'] >= 1 ? ($boletinDatos['bol_actualizaciones'] + 1) : 1;
	$actualizacion[$numActualizacion] = $nuevoArray;

	$update = [
		'bol_nota_indicadores'        => $sumaNotaIndicador,
		'bol_tipo'                    => 1,
		'bol_historial_actualizacion' => json_encode($actualizacion),
		'bol_estado'				  => 'GENERADO',
		'bol_actualizaciones'	      => $numActualizacion,
	];

	Boletin::actualizarNotaBoletin($config, $boletinDatos['bol_id'], $update);

}

$update = [
	'car_periodo' => $periodo + 1,
	'car_estado'  => 'SINTIA',
];

CargaAcademica::actualizarCargaPorID($config, $carga, $update);

include("../compartido/guardar-historial-acciones.php");
?>
<script language="javascript">
	window.location.href="../docente/page-info.php?idmsg=109&curso=<?=$_GET["grado"];?>&grupo=<?=$_GET["grupo"];?>&periodo=<?=$_GET["periodo"];?>";
</script>
<?php
exit();