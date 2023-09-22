<?php
    $nombrePagina="mps-empresas.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosMarketPlace.".empresas
        INNER JOIN ".$baseDatosMarketPlace.".empresas_categorias ON excat_empresa=emp_id
        INNER JOIN ".$baseDatosMarketPlace.".servicios_categorias ON svcat_id=excat_categoria
        LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=emp_institucion
        WHERE emp_eliminado!=1 AND ins_enviroment='".ENVIROMENT."' $filtro
        GROUP BY emp_id ORDER BY emp_id");
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