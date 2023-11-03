<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0125';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	mysqli_query($conexion, "UPDATE academico_actividad_foro SET foro_nombre='".mysqli_real_escape_string($conexion,$_POST["titulo"])."', foro_descripcion='".mysqli_real_escape_string($conexion,$_POST["contenido"])."' WHERE foro_id='".$_POST["idR"]."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="foros.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'";</script>';
exit();