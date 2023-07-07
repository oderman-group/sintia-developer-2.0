<?php
    $nombrePagina="dev-terminos-usuarios.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".terminos_tratamiento_politicas_usuarios
        INNER JOIN ".$baseDatosServicios.".instituciones ON ins_id=ttpxu_id_institucion AND ins_enviroment='".ENVIROMENT."'
        WHERE ttpxu_id_termino_tratamiento_politicas='".$id."' AND YEAR(ttpxu_fecha_aceptacion) =".$year." ".$filtro."
        ORDER BY ttpxu_id DESC;");
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