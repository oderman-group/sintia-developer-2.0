<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0166';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

//COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
if(trim($_POST["codigoM"])=="" or trim($_POST["nombreM"])=="" or trim($_POST["siglasM"])=="" or trim($_POST["areaM"])==""){
    echo '<script type="text/javascript">window.location.href="asignaturas-editar.php?error=ER_DT_4";</script>';
    exit();
}

if(empty($_POST["porcenAsigna"])) {$_POST["porcenAsigna"] = '';}
try{
    mysqli_query($conexion, "UPDATE ".BD_ACADEMICA.".academico_materias SET mat_codigo='".$_POST["codigoM"]."', mat_nombre='".$_POST["nombreM"]."', mat_siglas='".$_POST["siglasM"]."', mat_area='".$_POST["areaM"]."', mat_oficial=1, mat_valor='".$_POST["porcenAsigna"]."' WHERE mat_id='".$_POST["idM"]."' AND institucion={$config['conf_id_institucion']} AND year={$_SESSION["bd"]}");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");
    
    echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_2&id='.base64_encode($_POST["idM"]).'";</script>';
		exit();