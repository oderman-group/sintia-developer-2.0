<?php
    $nombrePagina="dev-modulos.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".modulos
    WHERE mod_id=mod_id $filtro
    ORDER BY mod_id;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }