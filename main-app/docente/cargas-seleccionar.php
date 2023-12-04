<?php
include("session.php");
Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DC0145';
include(ROOT_PATH."/main-app/compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/CargaAcademica.php");

$carga = base64_decode($_GET["carga"]);
$periodo = base64_decode($_GET["periodo"]);

if(!empty($carga)){
    setcookie("carga",$carga);
    setcookie("periodo",$periodo);
    
    $infoCargaActual = CargaAcademica::cargasDatosEnSesion($carga, $_SESSION["id"]);
    $_SESSION["infoCargaActual"] = $infoCargaActual;

    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="pagina-opciones.php?carga='.$_GET["carga"].'&periodo='.$_GET["periodo"].'";</script>';
    exit();
}else{
    include(ROOT_PATH."/main-app/compartido/guardar-historial-acciones.php");
    echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=100";</script>';
    exit();
}