<?php include("session.php"); ?>
<?php include("../modelo/conexion.php"); ?>
<?php
    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreM"])=="" || trim($_POST["areaM"])==""){
        echo '<script type="text/javascript">window.location.href="asignaturas-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    if(empty($_POST["siglasM"])) {$_POST["siglasM"] = substr($_POST["nombreM"], 0, 3);}
	$codigoAsignatura = "ASG".strtotime("now");

    mysqli_query($conexion, "INSERT INTO academico_materias(
        mat_codigo, 
        mat_nombre, 
        mat_siglas, 
        mat_area, 
        mat_oficial, 
        mat_valor
    )
    VALUES (
        '".$codigoAsignatura."', 
        '".$_POST["nombreM"]."', 
        '".strtoupper($_POST["siglasM"])."', 
        '".$_POST["areaM"]."', 
        1, 
        '".$_POST["porcenAsigna"]."'
    )");
	$idRegistro=mysqli_insert_id($conexion);
    
	echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_1&id='.$idRegistro.'";</script>';
    exit();