<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0119';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$date = date('Y-m-d', strtotime(str_replace('-', '/', $_POST["fecha"])));

try{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cronograma SET cro_tema='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', cro_fecha='".$date."', cro_recursos='".$_POST["recursos"]."', cro_color='".$_POST["colorFondo"]."' 
	WHERE cro_id='".$_POST["idR"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cronograma-calendario.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
exit();