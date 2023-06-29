<?php
    $nombrePagina="usuarios.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    $consulta = mysqli_query($conexion, "SELECT * FROM usuarios
    INNER JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=uss_tipo
    WHERE uss_id=uss_id $filtro
    ORDER BY uss_id");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }