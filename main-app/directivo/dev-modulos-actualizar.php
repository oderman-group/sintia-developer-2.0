<?php
include("session.php");

Modulos::validarAccesoPaginas();
$idPaginaInterna = 'DV0013';
include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreModulo"])==""){
        echo '<script type="text/javascript">window.location.href="dev-modulos-editar.php?error=ER_DT_4";</script>';
        exit();
    }

try{
    mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".modulos SET mod_nombre='".$_POST["nombreModulo"]."', mod_estado='".$_POST["moduloEstado"]."', mod_padre='".$_POST["moduloPadre"]."' WHERE mod_id='".$_POST["id"]."'");
} catch (Exception $e) {
	include("../compartido/error-catch-to-report.php");
}
	include("../compartido/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="dev-modulos.php?success=SC_DT_2&id='.$_POST["id"].'";</script>';
	exit();