<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0257';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

try {
    $itemsConsulta = mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".transaction_items SET description='".$_REQUEST['descripcion']."' WHERE id='".$_REQUEST['idItem']."' AND institucion = {$config['conf_id_institucion']} AND year = {$_SESSION["bd"]}");
} catch(Exception $e) {
    echo $e->getMessage();
    exit();
}

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");