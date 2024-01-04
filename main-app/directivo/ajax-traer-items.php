<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0252';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Movimientos.php");

// Obtener el resultado
$idTransaction = $_REQUEST["idTransaction"];
                                                                
$itemsConsulta = Movimientos::listarItemsTransaction($conexion, $config, $idTransaction);

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
    <td>
        <input type="number" min="0" id="precio<?=$fila['idtx'];?>" data-precio="<?=$fila['priceTransaction'];?>" onchange="actualizarSubtotal('<?=$fila['idtx'];?>')" value="<?=$fila['priceTransaction']?>">
    </td>
    <td>
        <textarea  id="descrip<?=$fila['idtx'];?>" cols="30" rows="1" onchange="guardarDescripcion('<?=$fila['idtx'];?>')"><?=$fila['description']?></textarea>
    </td>
    <td><input type="number" title="cantity" min="0" id="cantidadItems<?=$fila['idtx'];?>" data-cantidad="<?=$fila['cantity'];?>" name="cantidadItems" onchange="actualizarSubtotal('<?=$fila['idtx'];?>')" value="<?=$fila['cantity'];?>" style="width: 50px;"></td>
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
        idSubtotal.dataset.subtotalAnteriorSub = subtotal;

        idValorAdicional.innerHTML = '';
        idValorAdicional.appendChild(document.createTextNode(vlrAdicionalFinal));
        idValorAdicional.dataset.valorAdicional = vlrAdicional;
        
        idTotalNeto.innerHTML = '';
        idTotalNeto.appendChild(document.createTextNode(totalFinal));
        idTotalNeto.dataset.totalNeto = total;
        idTotalNeto.dataset.totalNetoAnterior = total;
    </script>
<?php
    require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
?>