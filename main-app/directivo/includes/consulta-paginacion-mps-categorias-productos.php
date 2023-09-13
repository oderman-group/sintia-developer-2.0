<?php
    $nombrePagina="mps-categorias-productos.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM " . $baseDatosMarketPlace . ".categorias_productos
        WHERE catp_eliminado!=1 $filtro
        ORDER BY catp_id;");
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