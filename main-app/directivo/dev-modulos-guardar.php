<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0016';
include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreModulo"])==""){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="dev-modulos-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    try{
        mysqli_query($conexion, "INSERT INTO ".$baseDatosServicios.".modulos(
            mod_nombre, 
            mod_estado, 
            mod_padre
        )
        VALUES (
            '".$_POST["nombreModulo"]."', 
            1, 
            '".$_POST["moduloPadre"]."'
        )");
        $idRegistro=mysqli_insert_id($conexion);
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }

    include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="dev-modulos.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
    exit();