<?php
    $nombrePagina="dev-instituciones.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".instituciones
        LEFT JOIN ".$baseDatosServicios.".planes_sintia ON plns_id=ins_id_plan
        WHERE ins_id=ins_id AND ins_enviroment='".ENVIROMENT."' $filtro
        ORDER BY ins_id;");
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