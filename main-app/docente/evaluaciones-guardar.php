<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0120';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

if(empty($_POST["bancoDatos"]) || $_POST["bancoDatos"]==0){
	try{
		mysqli_query($conexion, "INSERT INTO academico_actividad_evaluaciones(eva_nombre, eva_descripcion, eva_id_carga, eva_periodo, eva_estado, eva_desde, eva_hasta, eva_clave)"." VALUES('".mysqli_real_escape_string($conexion,$_POST["titulo"])."', '".mysqli_real_escape_string($conexion,$_POST["contenido"])."', '".$cargaConsultaActual."', '".$periodoConsultaActual."', 1, '".$_POST["desde"]."', '".$_POST["hasta"]."', '".mysqli_real_escape_string($conexion,$_POST["clave"])."')");
	} catch (Exception $e) {
		include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
	}
	$idRegistro = mysqli_insert_id($conexion);
}else{
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="preguntas-agregar.php?carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&idE='.base64_encode($idRegistro).'&success=SC_GN_1";</script>';
exit();