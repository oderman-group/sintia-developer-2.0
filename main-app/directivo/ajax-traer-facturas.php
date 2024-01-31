<?php
include("session.php");
$idPaginaInterna = 'DT0270';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

$filtro='';
if (!empty($_REQUEST["idUsuario"])){
    $filtro = " AND fcu_usuario='".$_REQUEST["idUsuario"]."'";
}

$consulta = Movimientos::listarFacturas($conexion, $config, $filtro);
$numFacturas = mysqli_num_rows($consulta);

if ($numFacturas > 0) {
    while ($resultado = mysqli_fetch_array($consulta, MYSQLI_BOTH)) {

        $vlrAdicional = !empty($resultado['fcu_valor']) ? $resultado['fcu_valor'] : 0;
        $totalNeto = Movimientos::calcularTotalNeto($conexion, $config, $resultado['fcu_id'], $vlrAdicional);
        $abonos = Movimientos::calcularTotalAbonado($conexion, $config, $resultado['fcu_id']);
        $porCobrar = $totalNeto - $abonos;
        $disabled = $porCobrar < 1 ? "disabled" : "";
?>
    <tr id="reg<?=$resultado['fcu_id'];?>">
        <td><?=$resultado['fcu_id'];?></td>
        <td id="totalNeto<?=$resultado['fcu_id'];?>" data-total-neto="<?=$totalNeto?>">$<?=number_format($totalNeto, 0, ",", ".")?></td>
        <td style="color: green;" id="abonos<?=$resultado['fcu_id'];?>" data-abonos="<?=$abonos?>">$<?=number_format($abonos, 0, ",", ".")?></td>
        <td style="color: red;" id="porCobrar<?=$resultado['fcu_id'];?>" data-por-cobrar="<?=$porCobrar?>">$<?=number_format($porCobrar, 0, ",", ".")?></td>
        <td>
            <input type="number" min="0" onchange="actualizarAbonado(this)" data-id-factura="<?=$resultado['fcu_id'];?>" data-id-abono="<?=$_REQUEST["idAbono"];?>" data-abono-anterior="0" value="0" <?=$disabled?>>
        </td>
    </tr>
<?php 
    }
} else {
?>
    <tr>
        <td colspan="5" align="center" style="font-size: 17px; font-weight:bold;">No se encontraron facturas para este cliente...</td>
    </tr>
<?php 
}

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
exit();