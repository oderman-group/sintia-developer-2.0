<?php
    $nombrePagina="dev-contrato-usuarios.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    try{
        $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".contratos_usuarios
        LEFT JOIN ".$baseDatosServicios.".contratos ON cont_id=cxu_id_contrato
        LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=cxu_id_institucion AND ins_enviroment='".ENVIROMENT."'
        WHERE  YEAR(cxu_fecha_aceptacion) =".$year." ".$filtro."
        ORDER BY cxu_id DESC;");
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