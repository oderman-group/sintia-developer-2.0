<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0254';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Utilidades.php");

$idInsercion=Utilidades::generateCode("TXI_");
try {
    mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".transaction_items(id, id_transaction, type_transaction, discount, cantity, subtotal, id_item, institucion, year)VALUES('".$idInsercion."', '".$_REQUEST['idTransaction']."', 'INVOICE', 0, 1, '".$_REQUEST['precio']."', '".$_REQUEST['idItem']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
} catch(Exception $e) {
    echo $e->getMessage();
    exit();
}

$arrayIdInsercion=["idInsercion"=>$idInsercion];

header('Content-Type: application/json');
echo json_encode($arrayIdInsercion);

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");