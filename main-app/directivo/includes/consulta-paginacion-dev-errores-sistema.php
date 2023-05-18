<?php
    $nombrePagina="dev-errores-sistema.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".reporte_errores
    LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=rperr_institucion
    LEFT JOIN usuarios ON uss_id=rperr_usuario
    WHERE rperr_id=rperr_id $filtro
    ORDER BY rperr_id DESC;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }