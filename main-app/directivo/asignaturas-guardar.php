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
    require_once(ROOT_PATH."/main-app/class/Utilidades.php");
    $codigo=Utilidades::generateCode("MAT");

    if(empty($_POST["siglasM"])) {$_POST["siglasM"] = substr($_POST["nombreM"], 0, 3);}
    if(empty($_POST["porcenAsigna"])) {$_POST["porcenAsigna"] = '';}
	$codigoAsignatura = "ASG".strtotime("now");

    try{
        mysqli_query($conexion, "INSERT INTO ".BD_ACADEMICA.".academico_materias(
            mat_id, 
            mat_codigo, 
            mat_nombre, 
            mat_siglas, 
            mat_area, 
            mat_oficial, 
            mat_valor, 
            institucion, 
            year
        )
        VALUES (
            '".$codigo."', 
            '".$codigoAsignatura."', 
            '".$_POST["nombreM"]."', 
            '".strtoupper($_POST["siglasM"])."', 
            '".$_POST["areaM"]."', 
            1, 
            '".$_POST["porcenAsigna"]."', 
            {$config['conf_id_institucion']}, 
            {$_SESSION["bd"]}
        )");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    
	include("../compartido/guardar-historial-acciones.php");
	echo '<script type="text/javascript">window.location.href="asignaturas.php?success=SC_DT_1&id='.base64_encode($codigo).'";</script>';
    exit();