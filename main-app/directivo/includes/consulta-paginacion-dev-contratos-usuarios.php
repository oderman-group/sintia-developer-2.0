<?php
    $nombrePagina="dev-contrato-usuarios.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".contratos_usuarios
    LEFT JOIN ".$baseDatosServicios.".contratos ON cont_id=cxu_id_contrato
    LEFT JOIN ".$baseDatosServicios.".instituciones ON ins_id=cxu_id_institucion
    WHERE  YEAR(cxu_fecha_aceptacion) =".$year." ".$filtro."
    ORDER BY cxu_id DESC;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }