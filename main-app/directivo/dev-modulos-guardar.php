<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreModulo"])==""){
        echo '<script type="text/javascript">window.location.href="dev-modulos-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

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
    
	echo '<script type="text/javascript">window.location.href="dev-modulos.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
    exit();