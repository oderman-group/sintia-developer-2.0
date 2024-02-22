<?php
include("session.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0094';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}

if (empty($_POST["fecha"]) or empty($_POST["detalle"]) or (isset($_POST["valor"]) && $_POST["valor"]=="") or empty($_POST["tipo"]) or empty($_POST["forma"])) {
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="movimientos-agregar.php?error=ER_DT_4";</script>';
    exit();
}
$consecutivo = '';

if ($_POST["tipo"] == 1) {

    try{
        $consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM ".BD_FINANCIERA.".finanzas_cuentas WHERE fcu_tipo=1 AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}
        ORDER BY fcu_id DESC");
    } catch (Exception $e) {
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
    $consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
    if (empty($consecutivoActual['fcu_consecutivo'])) {
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
        include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
    }
    $consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
    if (empty($consecutivoActual['fcu_consecutivo'])) {
        $consecutivo = $config['conf_inicio_recibos_egreso'];
    } else {
        $consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
    }
}

$idInsercion=Utilidades::generateCode("FCU");
try{
    mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".finanzas_cuentas(fcu_id, fcu_fecha, fcu_detalle, fcu_valor, fcu_tipo, fcu_observaciones, fcu_usuario, fcu_anulado, fcu_forma_pago, fcu_cerrado, fcu_consecutivo, institucion, year)VALUES('" .$idInsercion . "', '" . $_POST["fecha"] . "','" . $_POST["detalle"] . "','" . $_POST["valor"] . "','" . $_POST["tipo"] . "','" . $_POST["obs"] . "','" . $_POST["usuario"] . "',0,'" . $_POST["forma"] . "',0,'" . $consecutivo . "', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

try{
    mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".transaction_items SET id_transaction='" .$idInsercion . "' WHERE id_transaction='" . $_POST["idU"] . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
    include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
}

if (!empty($_POST["abonoAutomatico"]) && $_POST["abonoAutomatico"] == 1) {
    $totalNeto    = Movimientos::calcularTotalNeto($conexion, $config, $idInsercion, $_POST["valor"]);

    if ($totalNeto > 0) {
        $codigoUnico=Utilidades::generateCode("ABO");
        try {
            mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".payments (responsible_user, invoiced, cod_payment, type_payments, payment_method, observation, institucion, year)VALUES('{$_SESSION["id"]}', '".$_POST["usuario"]."', '".$codigoUnico."', '".INVOICE."', 'TRANSFERENCIA', 'Abono automatico', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        try {
            mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".payments_invoiced (invoiced, payments, payment, institucion, year)VALUES('".$idInsercion."', '".$codigoUnico."', '".$totalNeto."', {$config['conf_id_institucion']}, {$_SESSION["bd"]});");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }

        try{
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".finanzas_cuentas SET fcu_status='" .COBRADA. "' WHERE fcu_id='" . $idInsercion . "' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch (Exception $e) {
            include(ROOT_PATH."/main-app/compartido/error-catch-to-report.php");
        }
    }
}

include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");

echo '<script type="text/javascript">window.location.href="movimientos-editar.php?success=SC_DT_1&id='.base64_encode($idInsercion).'";</script>';
exit();