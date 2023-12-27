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
    AND ti.year = {$_SESSION["bd"]}
    ORDER BY id_autoincremental";
    $itemsConsulta = mysqli_query($conexion, $consulta);
} catch(Exception $e) {
    echo $e->getMessage();
    exit();
}
$subtotal=0;
$numItems=mysqli_num_rows($itemsConsulta);
if($numItems>0){
    // Manejar el resultado según tus necesidades
    while ($fila = mysqli_fetch_array($itemsConsulta, MYSQLI_BOTH)) {
        $arrayEnviar = array("tipo"=>1, "restar"=>$fila['subtotal'], "descripcionTipo"=>"Para ocultar fila del registro.");
        $arrayDatos = json_encode($arrayEnviar);
        $objetoEnviar = htmlentities($arrayDatos);
?>
<tr id="reg<?=$fila['idtx'];?>">
    <td><?=$fila['idtx'];?></td>
    <td><?=$fila['name'];?></td>
    <td id="precio<?=$fila['idtx'];?>" data-precio="<?=$fila['price'];?>">$<?=number_format($fila['price'], 0, ",", ".")?></td>
    <td><input type="number" title="cantity" min="0" id="cantidadItems<?=$fila['idtx'];?>" name="cantidadItems" onchange="actualizarSubtotal('<?=$fila['idtx'];?>')" value="<?=$fila['cantity'];?>" style="width: 50px;"></td>
    <td id="subtotal<?=$fila['idtx'];?>" data-subtotal-anterior="<?=$fila['subtotal'];?>">$<?=number_format($fila['subtotal'], 0, ",", ".")?></td>
    <td>
        <a href="#" title="<?=$objetoEnviar;?>" id="<?=$fila['idtx'];?>" name="movimientos-items-eliminar.php?idR=<?=$fila['idtx'];?>" style="padding: 4px 4px; margin: 5px;" class="btn btn-sm" onClick="deseaEliminar(this)">X</a>
    </td>
</tr>
<?php 
        $subtotal += $fila['subtotal'];
        }
    }
    if(empty($_REQUEST["vlrAdicional"])){ $_REQUEST["vlrAdicional"]=0; }
    $total= $subtotal+$_REQUEST["vlrAdicional"];
?>
    <script>
        var subtotal=       <?=$subtotal?>;
        var vlrAdicional=   <?=$_REQUEST["vlrAdicional"]?>;
        var total=          <?=$total?>;

        var idSubtotal = document.getElementById('subtotal');
        var idValorAdicional = document.getElementById('valorAdicional');
        var idTotalNeto = document.getElementById('totalNeto');
        
        var subtotalFinal = "$"+numberFormat(subtotal, 0, ',', '.');
        var vlrAdicionalFinal = "$"+numberFormat(vlrAdicional, 0, ',', '.');
        var totalFinal = "$"+numberFormat(total, 0, ',', '.');
        
        idSubtotal.innerHTML = '';
        idSubtotal.appendChild(document.createTextNode(subtotalFinal));
        idSubtotal.dataset.subtotal = subtotal;

        idValorAdicional.innerHTML = '';
        idValorAdicional.appendChild(document.createTextNode(vlrAdicionalFinal));
        idValorAdicional.dataset.valorAdicional = vlrAdicional;
        
        idTotalNeto.innerHTML = '';
        idTotalNeto.appendChild(document.createTextNode(totalFinal));
        idTotalNeto.dataset.totalNeto = total;
    </script>
<?php
    require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>