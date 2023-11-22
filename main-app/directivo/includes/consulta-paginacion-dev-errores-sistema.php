<?php
    $nombrePagina="dev-errores-sistema.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".reporte_errores
        INNER JOIN ".$baseDatosServicios.".instituciones ON ins_id=rperr_institucion AND ins_enviroment='".ENVIROMENT."'
        LEFT JOIN ".BD_GENERAL.".usuarios uss ON uss_id=rperr_usuario AND uss.institucion=rperr_institucion AND uss.year=rperr_year
        WHERE rperr_id=rperr_id $filtro
        ORDER BY rperr_id DESC;");
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