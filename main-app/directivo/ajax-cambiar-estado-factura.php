<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0301';
require_once(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");

    try {
        mysqli_query($conexion, "UPDATE ".BD_FINANCIERA.".finanzas_cuentas SET fcu_status='COBRADA' WHERE fcu_id='".$_REQUEST['idFactura']."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
    } catch(Exception $e) {
        echo $e->getMessage();
        exit();
    }

require_once(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
exit;