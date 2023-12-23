<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0252';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

// Obtener el resultado
$idTransaction = $_REQUEST["idTransaction"];
try {
    $consulta = "SELECT ti.id AS idtx, i.id AS idit, i.name, i.price, ti.cantity, ti.subtotal
    FROM ".BD_FINANCIERA.".transaction_items ti
    INNER JOIN ".BD_FINANCIERA.".items i ON i.id = ti.id_item
    WHERE ti.id_transaction = '{$idTransaction}'
    AND ti.type_transaction = 'INVOICE'
    AND ti.institucion = {$config['conf_id_institucion']}
    AND ti.year = {$_SESSION["bd"]}";
    $itemsConsulta = mysqli_query($conexion, $consulta);
} catch(Exception $e) {
    echo $e->getMessage();
    exit();
}
$numItems=mysqli_num_rows($itemsConsulta);
if($numItems>0){
    // Manejar el resultado según tus necesidades
    while ($fila = mysqli_fetch_array($itemsConsulta, MYSQLI_BOTH)) {
?>
<tr>
    <td><?=$fila['idtx'];?></td>
    <td><?=$fila['name'];?></td>
    <td id="precio<?=$fila['idtx'];?>"><?=$fila['price'];?></td>
    <td><input type="number" title="cantity" id="cantidadItems<?=$fila['idtx'];?>" name="cantidadItems" onchange="actualizarSubtotal('<?=$fila['idtx'];?>')" value="<?=$fila['cantity'];?>"></td>
    <td id="subtotal<?=$fila['idtx'];?>"><?=$fila['subtotal'];?></td>
</tr>
<?php 
    }
} 

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>