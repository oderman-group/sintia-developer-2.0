<?php 
include("session.php");

Modulos::validarAccesoDirectoPaginas();
$idPaginaInterna = 'DT0180';

if(!Modulos::validarSubRol([$idPaginaInterna])){
	echo '<script type="text/javascript">window.location.href="page-info.php?idmsg=301";</script>';
	exit();
}
include("../compartido/historial-acciones-guardar.php");

    //COMPROBAMOS QUE TODOS LOS CAMPOS NECESARIOS ESTEN LLENOS
    if(trim($_POST["nombreM"])=="" || trim($_POST["areaM"])==""){
        include("../compartido/guardar-historial-acciones.php");
        echo '<script type="text/javascript">window.location.href="asignaturas-agregar.php?error=ER_DT_4";</script>';
        exit();
    }

    if(empty($_POST["siglasM"])) {$_POST["siglasM"] = substr($_POST["nombreM"], 0, 3);}
    if(empty($_POST["porcenAsigna"])) {$_POST["porcenAsigna"] = '';}
	$codigoAsignatura = "ASG".strtotime("now");

    try{
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
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
	$idRegistro=mysqli_insert_id($conexion);
    
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_1&id='.base64_encode($idRegistro).'";</script>';
    exit();