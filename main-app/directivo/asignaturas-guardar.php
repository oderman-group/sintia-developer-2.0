<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0180';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreM"])=="" || trim($_POST["areaM"])==""){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="asignaturas-agregar.php?error=ER_DT_4";</script>';
        exit();
    }
    require_once(ROOT_PATH."/main-app/class/Asignaturas.php");
    
    $codigo = Asignaturas::guardarAsignatura($conexion, $conexionPDO, $config, $_POST);
    
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_1&id='.base64_encode($codigo).'";</script>';
    exit();