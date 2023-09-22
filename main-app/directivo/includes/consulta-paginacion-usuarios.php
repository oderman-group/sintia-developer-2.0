<?php
    $nombrePagina="usuarios.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
        INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
        WHERE uss_id=uss_id $filtro
        ORDER BY uss_id");
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