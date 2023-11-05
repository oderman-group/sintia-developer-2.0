<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0124';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	mysqli_query($conexion, "INSERT INTO academico_actividad_foro(foro_nombre, foro_descripcion, foro_id_carga, foro_periodo, foro_estado)VALUES('".mysqli_real_escape_string($conexion,$_POST["titulo"])."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$cargaConsultaActual."', '".$periodoConsultaActual."', 1)");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}
$idRegistro=mysqli_insert_id($conexion);

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="foros.php?success=SC_DT_1&id='.base64_encode($idRegistro).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();