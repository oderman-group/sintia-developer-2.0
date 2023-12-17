<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0130';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
$codigo=Utilidades::generateCode("PC");

$archivoSubido = new Archivos;

$archivoSubido->validarArchivo($_FILES['file']['size'], $_FILES['file']['name']);
$explode=explode(".", $_FILES['file']['name']);
$extension = end($explode);
$archivo = uniqid($_SESSION["inst"].'_'.$_SESSION["id"].'_file_').".".$extension;
$destino = ROOT_PATH."/main-app/files/pclase";
@unlink($destino."/".$archivo);
move_uploaded_file($_FILES['file']['tmp_name'], $destino ."/".$archivo);

try{
	mysqli_query($conexion, "DELETE FROM ".BD_ACADEMICA.".academico_pclase WHERE pc_id_carga='".$cargaConsultaActual."' AND pc_periodo='".$periodoConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
	mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_pclase(pc_id, pc_plan, pc_id_carga, pc_periodo, pc_fecha_subido, institucion, year)VALUES('".$codigo."', '".$archivo."', '".$cargaConsultaActual."', '".$periodoConsultaActual."', now(), {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="clases.php?success=SC_GN_4&carga='.base64_encode($cargaConsultaActual).'&periodo='.base64_encode($periodoConsultaActual).'&tab=3";</script>';
exit();