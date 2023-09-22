<?php
    $nombrePagina="mps-categorias-servicios.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM " . $baseDatosMarketPlace . ".servicios_categorias
        WHERE svcat_activa=1 $filtro
        ORDER BY svcat_id;");
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