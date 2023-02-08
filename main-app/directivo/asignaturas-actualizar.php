<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["codigoM"])=="" or trim($_POST["nombreM"])=="" or trim($_POST["siglasM"])=="" or trim($_POST["areaM"])==""){
        echo '<script type="text/javascript">window.location.href="asignaturas-editar.php?error=ER_DT_4";</script>';
        exit();
    }
    
    mysqli_query($conexion, "UPDATE academico_materias SET mat_codigo='".$_POST["codigoM"]."', mat_nombre='".$_POST["nombreM"]."', mat_siglas='".$_POST["siglasM"]."', mat_area=".$_POST["areaM"].", mat_oficial=1 WHERE mat_id='".$_POST["idM"]."'");
    
    echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_2&id='.$_POST["idM"].'";</script>';
		exit();