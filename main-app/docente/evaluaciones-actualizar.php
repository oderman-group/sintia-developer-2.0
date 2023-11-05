<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0122';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

try{
	mysqli_query($conexion, "UPDATE academico_actividad_evaluaciones SET eva_nombre='".mysqli_real_escape_string($conexion,$_POST["titulo"])."', eva_descripcion='".mysqli_real_escape_string($conexion,$_POST["contenido"])."', eva_desde='".$_POST["desde"]."', eva_hasta='".$_POST["hasta"]."', eva_clave='".mysqli_real_escape_string($conexion,$_POST["clave"])."' WHERE eva_id='".$_POST["idR"]."'");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="evaluaciones.php?success=SC_DT_2&id='.base64_encode($_POST["idR"]).'";</script>';
exit();