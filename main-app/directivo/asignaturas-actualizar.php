<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0166';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");
require_once(ROOT_PATH."/main-app/class/Asignaturas.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if(trim($_POST["codigoM"])=="" or trim($_POST["nombreM"])=="" or trim($_POST["siglasM"])=="" or trim($_POST["areaM"])==""){
    echo '<script type="text/javascript">window.location.href="asignaturas-editar.php?error=ER_DT_4";</script>';
    exit();
}

Asignaturas::actualizarAsignatura($conexion, $config, $_POST);

	include("../compartido/guardar-historial-acciones.php");
    
    echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_2&id='.base64_encode($_POST["idM"]).'";</script>';
		exit();