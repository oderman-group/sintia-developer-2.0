<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0115';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

include(ROOT_PATH."/main-app/compartido/sintia-funciones.php");
include("verificar-carga.php");
include("verificar-periodos-diferentes.php");

if(empty($_POST["indicadores"])) $_POST["indicadores"] = '0';
if(empty($_POST["calificaciones"])) $_POST["calificaciones"] = '0';
if(empty($_POST["fechaInforme"])) $_POST["fechaInforme"] = '2000-12-31';
if(empty($_POST["posicion"])) $_POST["posicion"] = '0';

try{
	mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_cargas SET car_valor_indicador='".$_POST["indicadores"]."', car_configuracion='".$_POST["calificaciones"]."', car_fecha_generar_informe_auto='".$_POST["fechaInforme"]."', car_posicion_docente='".$_POST["posicion"]."' WHERE car_id='".$cargaConsultaActual."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

$infoCargaActual = CargaAcademica::cargasDatosEnSesion(base64_decode($_GET["carga"]), $_SESSION["id"]);
$_SESSION["infoCargaActual"] = $infoCargaActual;

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
echo '<script type="text/javascript">window.location.href="cargas-configurar.php?carga='.$_GET["carga"].'&periodo='.$_GET["periodo"].'";</script>';
exit();