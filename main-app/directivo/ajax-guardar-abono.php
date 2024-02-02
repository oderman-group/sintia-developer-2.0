<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0300';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

if($_REQUEST['type'] == INVOICE){
    if($_REQUEST['abonoAnterior'] > 0){
        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".payments_invoiced SET payment='".$_REQUEST['abono']."' WHERE invoiced='".$_REQUEST['idFactura']."' AND payments='".$_REQUEST['idAbono']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }else{
        try {
            mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".payments_invoiced(payment, invoiced, payments, institucion, year)VALUES('".$_REQUEST['abono']."', '".$_REQUEST['idFactura']."', '".$_REQUEST['idAbono']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
}


if($_REQUEST['type'] == ACCOUNT){
    if(!empty($_REQUEST['conceptoModificar'])){
        $idInsercion=$_REQUEST['conceptoModificar'];
        try {
            mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".payments_invoiced SET invoiced='".$_REQUEST['concepto']."', payment='".$_REQUEST['precio']."', cantity='".$_REQUEST['cantidad']."', subtotal='".$_REQUEST['subtotal']."' WHERE id='".$_REQUEST['conceptoModificar']."' AND payments='".$_REQUEST['idAbono']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
        } catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }else{
        try {
            mysqli_query($conexion, "INSERT INTO ".BD_FINANCIERA.".payments_invoiced(invoiced, payments, institucion, year)VALUES('".$_REQUEST['concepto']."', '".$_REQUEST['idAbono']."', {$config['conf_id_institucion']}, {$_SESSION["bd"]})");
        } catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
        $idInsercion = mysqli_insert_id($conexion);
    }
}

$arrayIdInsercion=["idInsercion"=>$idInsercion];

header('Content-Type: application/json');
echo json_encode($arrayIdInsercion);

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
exit;