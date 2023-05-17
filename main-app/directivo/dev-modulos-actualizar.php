<?php
include("session.php");
include("../modelo/conexion.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreModulo"])==""){
        echo '<script type="text/javascript">window.location.href="dev-modulos-editar.php?error=ER_DT_4";</script>';
        exit();
    }
    
    mysqli_query($conexion, "UPDATE ".$baseDatosServicios.".modulos SET mod_nombre='".$_POST["nombreModulo"]."', mod_estado='".$_POST["moduloEstado"]."', mod_padre='".$_POST["moduloPadre"]."' WHERE mod_id='".$_POST["id"]."'");
    
    echo '<script type="text/javascript">window.location.href="dev-modulos.php?success=SC_DT_2&id='.$_POST["id"].'";</script>';
	exit();