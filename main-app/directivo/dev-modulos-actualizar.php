<?php
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DV0013';
include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreModulo"])==""){
        echo '<script type="text/javascript">window.location.href="dev-modulos-editar.php?error=ER_DT_4";</script>';
        exit();
    }

    if (!empty($_FILES['portada']['name'])) {
        $destino = ROOT_PATH.'/main-app/files/modulos';
        $explode = explode(".", $_FILES['portada']['name']);
        $extension = end($explode);
        $portada= uniqid('mod_') . "." . $extension;
        @unlink($destino . "/" . $portada);
        move_uploaded_file($_FILES['portada']['tmp_name'], $destino . "/" . $portada);

        try {
            mysqli_query($conexion, "UPDATE ".BD_ADMIN.".modulos SET mod_imagen='".$portada."' WHERE mod_id='".$_POST["id"]."'");
        } catch (Exception $e) {
            include("../compartido/error-catch-to-report.php");
        }
    }

    $clientes = "";
    if (!empty($_POST["clientes"])) {
        $clientes = implode(",", $_POST["clientes"]);
    }

    try{
        mysqli_query($conexion, "UPDATE ".BD_ADMIN.".modulos SET 
        mod_nombre='".$_POST["nombreModulo"]."', 
        mod_estado='".$_POST["moduloEstado"]."', 
        mod_padre='".$_POST["moduloPadre"]."', 
        mod_namespace='".$_POST["namespace"]."', 
        mod_description='".$_POST["descripcion"]."', 
        mod_precio='".$_POST["precio"]."', 
        mod_order='".$_POST["order"]."', 
        mod_types_customer='".$clientes."'
        WHERE mod_id='".$_POST["id"]."'");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
	include("../compartido/guardar-historial-acciones.php");

    echo '<script type="text/javascript">window.location.href="dev-modulos.php?success=SC_DT_2&id='.base64_encode($_POST["id"]).'";</script>';
	exit();