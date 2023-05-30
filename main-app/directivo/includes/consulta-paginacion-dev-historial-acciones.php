<?php
    $nombrePagina="dev-historial-acciones.php";
    if($_REQUEST["nume"] == "" ){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".seguridad_historial_acciones
    INNER JOIN ".$baseDatosServicios.".instituciones ON ins_id=hil_institucion AND ins_enviroment='".ENVIROMENT."'
    LEFT JOIN ".$baseDatosServicios.".paginas_publicidad ON pagp_id=hil_titulo
    WHERE  hil_institucion=".$instID." AND YEAR(hil_fecha) =".$year." AND MONTH(hil_fecha) =".$mes." ".$filtro."
    ORDER BY hil_id DESC;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }