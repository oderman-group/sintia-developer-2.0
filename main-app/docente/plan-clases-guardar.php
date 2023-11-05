<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0130';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

$archivoSubido = new Archivos;

$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
$explode=explode(".", $_FILES['file']['name']);
$extension = end($explode);
$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
$destino = ROOT_PATH."/main-app/files/pclase";
@unlink($destino."/".$archivo);
move_uploaded_file($_FILES['file']['tmp_name'], $destino ."/".$archivo);

try{
	mysqli_query($conexion, "DELETE FROM academico_pclase WHERE pc_id_carga='".$cargaConsultaActual."' AND pc_periodo='".$periodoConsultaActual."'");
	mysqli_query($conexion, "INSERT INTO academico_pclase(pc_plan, pc_id_carga, pc_periodo, pc_fecha_subido)VALUES('".$archivo."', '".$cargaConsultaActual."', '".$periodoConsultaActual."', now())");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?success=SC_GN_4&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=3";</script>';
exit();