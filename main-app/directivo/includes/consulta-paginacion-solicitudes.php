<?php
    $nombrePagina="solicitudes.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    $consulta = mysqli_query($conexion, "SELECT * FROM ".$baseDatosServicios.".general_solicitudes 
    LEFT JOIN usuarios ON uss_id=soli_remitente
    LEFT JOIN academico_matriculas ON mat_id=soli_id_recurso
    WHERE soli_institucion='".$config['conf_id_institucion']."' 
    AND soli_year='".$_SESSION["bd"]."' $filtro");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }