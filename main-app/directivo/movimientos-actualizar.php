<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0177';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

$fecha = null;
if(!empty($_POST["fecha"])) {
    $fecha = $_POST["fecha"];
}

if (empty($_POST["fecha"]) or empty($_POST["detalle"]) or (isset($_POST["valor"]) && $_POST["valor"]=="") or empty($_POST["tipo"]) or empty($_POST["forma"])) {
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="movimientos-editar.php?error=ER_DT_4";</script>';
    exit();
}

if ($_POST["tipo"] == 1) {
    try{
        $consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
        ORDER BY fcu_id DESC");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }

    $consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
    if ($consecutivoActual['fcu_consecutivo'] == "") {
        $consecutivo = $config['conf_inicio_recibos_ingreso'];
    } else {
        $consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
    }
}

if ($_POST["tipo"] == 2) {
    try{
        $consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=2 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
        ORDER BY fcu_id DESC");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
    if ($consecutivoActual['fcu_consecutivo'] == "") {
        $consecutivo = $config['conf_inicio_recibos_egreso'];
    } else {
        $consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
    }
}

try{
    mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".finanzas_cuentas SET
    fcu_fecha='".$fecha."',
    fcu_detalle='".$_POST["detalle"]."',
    fcu_valor='".$_POST["valor"]."',
    fcu_tipo='".$_POST["tipo"]."',
    fcu_observaciones='".$_POST["obs"]."',
    fcu_usuario='".$_POST["usuario"]."',
    fcu_anulado='".$_POST["anulado"]."',
    fcu_forma_pago='".$_POST["forma"]."',
    fcu_cerrado='".$_POST["estado"]."',
    fcu_consecutivo='" . $consecutivo . "'

    WHERE fcu_id='".$_POST['idU']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="movimientos-editar.php?success=SC_DT_2&id='.base64_encode($_POST['idU']).'";</script>';
exit();