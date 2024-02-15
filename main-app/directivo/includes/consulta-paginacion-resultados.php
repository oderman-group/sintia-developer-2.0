<?php
    $nombrePagina="encuestas-resultados.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = base64_encode(1);}
    $consulta = Asignaciones::resultadoEncuestasFinalizadas($conexion, $config, $idE, $filtro);
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=base64_decode($_REQUEST["nume"]);
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }