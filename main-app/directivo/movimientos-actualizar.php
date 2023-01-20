<?php 
include("session.php");
include("../modelo/conexion.php");
if (trim($_POST["fecha"]) == "" or trim($_POST["detalle"]) == "" or trim($_POST["valor"]) == "" or trim($_POST["tipo"]) == "" or trim($_POST["forma"]) == "") {
    echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
    exit();
}
if ($_POST["tipo"] == 1) {
    $consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_tipo=1 ORDER BY fcu_id DESC");
    $consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
    if ($consecutivoActual['fcu_consecutivo'] == "") {
        $consecutivo = $config['conf_inicio_recibos_ingreso'];
    } else {
        $consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
    }
}
if ($_POST["tipo"] == 2) {
    $consultaConsecutivoActual=mysqli_query($conexion, "SELECT * FROM finanzas_cuentas WHERE fcu_tipo=2 ORDER BY fcu_id DESC");
    $consecutivoActual = mysqli_fetch_array($consultaConsecutivoActual, MYSQLI_BOTH);
    if ($consecutivoActual['fcu_consecutivo'] == "") {
        $consecutivo = $config['conf_inicio_recibos_egreso'];
    } else {
        $consecutivo = $consecutivoActual['fcu_consecutivo'] + 1;
    }
}
mysqli_query($conexion, "UPDATE finanzas_cuentas SET
fcu_fecha='".$_POST["fecha"]."',
fcu_detalle='".$_POST["detalle"]."',
fcu_valor='".$_POST["valor"]."',
fcu_tipo='".$_POST["tipo"]."',
fcu_observaciones='".$_POST["obs"]."',
fcu_usuario='".$_POST["usuario"]."',
fcu_anulado='".$_POST["anulado"]."',
fcu_forma_pago='".$_POST["forma"]."',
fcu_cerrado='".$_POST["estado"]."',
fcu_consecutivo='" . $consecutivo . "'

WHERE fcu_id='".$_POST['idU']."'");
$lineaError = __LINE__;

include("../compartido/reporte-errores.php");
echo '<script type="text/javascript">window.location.href="movimientos.php";</script>';
exit();