<?php
    $nombrePagina="dev-paginas.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    $consulta=mysqli_query($conexion,"SELECT * FROM ".$baseDatosServicios.".paginas_publicidad
    LEFT JOIN ".$baseDatosServicios.".modulos ON mod_id=pagp_modulo
    LEFT JOIN ".$baseDatosServicios.".general_perfiles ON pes_id=pagp_tipo_usuario
    WHERE pagp_id=pagp_id $filtro
    ORDER BY pagp_id;");
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }