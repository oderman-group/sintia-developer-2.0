<?php
    $nombrePagina="estudiantes.php";
    if(empty($_REQUEST["nume"])){$_REQUEST["nume"] = "1";}
    $consulta = Estudiantes::listarEstudiantes(0, $filtro, '');
    $numRegistros=mysqli_num_rows($consulta);
    $registros= $config['conf_num_registros'];
    $pagina=$_REQUEST["nume"];
    if (is_numeric($pagina)){
        $inicio= (($pagina-1)*$registros);
    }			     
    else{
        $inicio=1;
    }