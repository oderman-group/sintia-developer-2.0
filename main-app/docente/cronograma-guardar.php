<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0118';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

$date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));

$idInsercion=Utilidades::generateCode("CRO");
try{
	mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_cronograma(cro_id, cro_tema, cro_fecha, cro_id_carga, cro_recursos, cro_periodo, cro_color, institucion, year)"." VALUES('" .$idInsercion . "', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$date."', '".$cargaConsultaActual."', '".$_POST["recursos"]."', '".$periodoConsultaActual."', '".$_POST["colorFondo"]."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cronograma-calendario.php?success=SC_DT_1&id='.base64_encode($idInsercion).'";</script>';
exit();