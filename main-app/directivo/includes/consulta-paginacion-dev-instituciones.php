<?php
    $nombrePagina="dev-instituciones.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".instituciones
    LEFT JOIN ".$baseDatosServicios.".planes_sintia ON plns_id=ins_id_plan
    WHERE ins_id=ins_id $filtro
    ORDER BY ins_id;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }