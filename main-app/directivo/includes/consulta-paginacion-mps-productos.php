<?php
    $nombrePagina="mps-productos.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosMarketPlace.".productos
        LEFT JOIN ".$baseDatosMarketPlace.".categorias_productos ON catp_id=prod_categoria
        LEFT JOIN ".$baseDatosMarketPlace.".empresas ON emp_id=prod_empresa
        WHERE prod_estado!=1 $filtro
        ORDER BY prod_id");
    } catch (Exception $e) {
        include("../compartido/error-catch-to-report.php");
    }
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }