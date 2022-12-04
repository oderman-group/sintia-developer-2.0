<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    /*
    if(trim($_POST["codigoM"])=="" or trim($_POST["nombreM"])=="" or trim($_POST["siglasM"])=="" or trim($_POST["areaM"])=="" or trim($_POST["oficial"])==""){
        echo "<span style='font-family:Arial; color:red;'>Debe llenar todos los campos.</samp>";
        exit();
    }
    */
    mysql_query("UPDATE academico_materias SET mat_codigo='".$_POST["codigoM"]."', mat_nombre='".$_POST["nombreM"]."', mat_siglas='".$_POST["siglasM"]."', mat_area=".$_POST["areaM"].", mat_oficial=1 WHERE mat_id='".$_POST["idM"]."'",$conexion);
    if(mysql_errno()!=0){echo mysql_error(); exit();}
    echo '<script type="text/javascript">window.location.href="'.$_SERVER['HTTP_REFERER'].'";</script>';
		exit();