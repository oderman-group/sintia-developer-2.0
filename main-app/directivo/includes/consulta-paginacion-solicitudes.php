<?php
    $nombrePagina="solicitudes.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_solicitudes 
        LEFT JOIN usuarios ON uss_id=soli_remitente
        LEFT JOIN academico_matriculas ON mat_id=soli_id_recurso
        WHERE soli_institucion='".$config['conf_id_institucion']."' 
        AND soli_year='".$_SESSION["bd"]."' $filtro");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=base64_decode($_REQUEST["nume"]);
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }